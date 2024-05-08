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
                  <label for="content" class="form-label">Suplier</label>
                  <select class="form-control" v-model="pembelian.id_suplier">
                    <option selected disabled value="">PILIH SUPLIER</option>
                    <option v-for="suplier in supliers" :key="suplier.id" :value="suplier.id">
                      {{ suplier.nama_suplier }}
                    </option>
                  </select>
                  <!-- validation -->
                  <div v-if="validation.id_suplier" class="mt-2 alert alert-danger">
                    {{ validation.id_suplier[0] }}
                  </div>
                </div>
                <div class="mb-3">
                <label for="idPembelian" class="form-label">ID</label>
                <input type="text" class="form-control" id="idPembelian" v-model="pembelian.id" placeholder="Masukkan nama barang" :disabled="true">
              </div>
                <div class="mb-3">
                  <label for="tanggal" class="form-label">Tanggal</label>
                  <input type="date" class="form-control" id="tanggal" v-model="pembelian.tgl" placeholder="Masukkan tanggal">
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
            const pembelian = reactive({
                tgl: '',
                id:0,
                id_suplier:0,
            })
            let supliers = ref([]);

            //state validation
            const validation = ref([]);
            onMounted(() => {
              //get API from Laravel Backend
              axios
                .get("http://149.28.137.82/api/supliers")
                .then((response) => {
                  //assign state posts with response data
                  supliers.value = response.data.data;
                })
                .catch((error) => {
                  console.log(error.response.data);
                });
            });
            onMounted(() =>{

                axios
                .get(`http://149.28.137.82/api/headers/${route.params.id}`)
                .then((response) => {
                //assign state posts with response data
                pembelian.tgl=response.data.data.tgl,
                pembelian.id_suplier=response.data.data.id_suplier,
                pembelian.id=response.data.data.id
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
                let tgl = pembelian.tgl;
                let id_suplier = pembelian.id_suplier

                //send server with axios
                axios.put(`http://149.28.137.82/api/headers/${route.params.id}`, {
                        tgl: tgl,
                        id_suplier: id_suplier,
                })
                .then(() => {

                    //redirect ke halaman login
                    return router.push({
                        name: 'pembelian.index'
                    })

                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                token,
                pembelian,
                validation,
                route,
                supliers,
                logout,
                update
            }

        }

    }
</script>