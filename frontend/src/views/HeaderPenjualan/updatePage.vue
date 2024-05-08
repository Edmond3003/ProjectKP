<template>
  <div class="background-image">
    <div class="container mt-5">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">MAIN MENU</h5>
              <hr>
              <ul class="list-group">
                <router-link :to="{ name: 'barang.index' }" class="list-group-item list-group-item-action">MASTER ATK</router-link>
                <router-link :to="{ name: 'kelompok.index' }" class="list-group-item list-group-item-action">KELOMPOK</router-link>
                <router-link :to="{ name: 'suplier.index' }" class="list-group-item list-group-item-action">SUPLIER</router-link>
                <router-link :to="{ name: 'cabang.index' }" class="list-group-item list-group-item-action">CABANG</router-link>
                <router-link :to="{ name: 'pembelian.index' }" class="list-group-item list-group-item-action">PEMBELIAN</router-link>
                <router-link :to="{ name: 'penjualan.index' }" class="list-group-item list-group-item-action">PENJUALAN</router-link>
                <router-link :to="{ name: 'laporanMS' }" class="list-group-item list-group-item-action">LAPORAN HARIAN</router-link>
                <li @click.prevent="logout" class="list-group-item list-group-item-action" style="cursor: pointer;">LOGOUT</li>
              </ul>
            </div>
          </div>
        </div>
  
        <!-- Main Content -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Update</h5>
              <hr>
              <form @submit.prevent="update">
                <div class="form-group mb-3">
                  <label for="content" class="form-label">Cabang</label>
                  <select class="form-control" v-model="penjualan.id_cabang">
                    <option selected disabled value="">PILIH SUPLIER</option>
                    <option v-for="cabang in cabangs" :key="cabang.id" :value="cabang.id">
                      {{ cabang.nama_cabang }}
                    </option>
                  </select>
                  <!-- validation -->
                  <div v-if="validation.id_cabang" class="mt-2 alert alert-danger">
                    {{ validation.id_cabang[0] }}
                  </div>
                </div>
                <div class="mb-3">
                <label for="idPenjualan" class="form-label">ID</label>
                <input type="text" class="form-control" id="idPenjualan" v-model="penjualan.id" placeholder="Masukkan nama barang" :disabled="true">
              </div>
                <div class="mb-3">
                  <label for="tanggal" class="form-label">Tanggal</label>
                  <input type="date" class="form-control" id="tanggal" v-model="penjualan.tgl" placeholder="Masukkan tanggal">
                  <!-- Validation -->
                  <div v-if="validation.tgl" class="mt-2 alert alert-danger">
                    {{ validation.tgl[0] }}
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">UPDATE</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.background-image {
    background-image: url('https://cdn.kibrispdr.org/data/9/background-alat-tulis-kantor-27.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    height: 100vh;
    display: flex;
    justify-content: center;
  }
</style>

<script>
    import { onMounted, reactive, ref } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import axios from 'axios'

    export default {

        setup() {
            const token = localStorage.getItem('token')
            //inisialisasi vue router on Composition API
            const router = useRouter()
            const route = useRoute()
            //state user
            const penjualan = reactive({
                tgl: '',
                id:0,
                id_cabang:0,
            })
            let cabangs = ref([]);

            //state validation
            const validation = ref([]);
            onMounted(() => {
              //get API from Laravel Backend
              axios
                .get("http://149.28.137.82/api/cabangs")
                .then((response) => {
                  //assign state posts with response data
                  cabangs.value = response.data.data;
                })
                .catch((error) => {
                  console.log(error.response.data);
                });
            });
            onMounted(() =>{

                axios
                .get(`http://149.28.137.82/api/headerjuals/${route.params.id}`)
                .then((response) => {
                //assign state posts with response data
                penjualan.tgl=response.data.data.tgl,
                penjualan.id_cabang=response.data.data.id_cabang,
                penjualan.id=response.data.data.id
                })
                .catch((error) => {
                console.log(error.response.data);
                });
            })

                //method logout
            function logout() {

                //logout
                axios.defaults.headers.common.Authorization = `Bearer ${token}`
                axios.post('http://149.28.137.82/api/logout')
                .then(response => {

                    if(response.data.success) {

                        //remove localStorage
                        localStorage.removeItem('token')

                        //redirect ke halaman login
                        return router.push({
                            name: 'login'
                        })

                    }

                })
                .catch(error => {
                    console.log(error.response.data)
                })

            }
            //method register
            function update() {

                //define variable 
                let tgl = penjualan.tgl;
                let id_cabang = penjualan.id_cabang

                //send server with axios
                axios.put(`http://149.28.137.82/api/headerjuals/${route.params.id}`, {
                        tgl: tgl,
                        id_cabang: id_cabang,
                })
                .then(() => {

                    //redirect ke halaman login
                    return router.push({
                        name: 'penjualan.index'
                    })

                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                token,
                penjualan,
                validation,
                route,
                cabangs,
                logout,
                update
            }

        }

    }
</script>