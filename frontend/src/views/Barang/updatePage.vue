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
                  <label for="content" class="form-label">KELOMPOK</label>
                  <select class="form-control" v-model="barang.id_kelompok">
                    <option selected disabled value="">PILIH KELOMPOK</option>
                    <option v-for="kelompok in kelompoks" :key="kelompok.id" :value="kelompok.id">
                      {{ kelompok.nama_kelompok }}
                    </option>
                  </select>
                  <!-- validation -->
                  <div v-if="validation.id_kelompok" class="mt-2 alert alert-danger">
                    {{ validation.id_kelompok[0] }}
                  </div>
                </div>
                <div class="mb-3">
                <label for="idBarang" class="form-label">ID</label>
                <input type="text" class="form-control" id="idBarang" v-model="barang.id" placeholder="Masukkan nama barang" :disabled="true">
              </div>
                <div class="mb-3">
                  <label for="namaBarang" class="form-label">Nama Barang</label>
                  <input type="text" class="form-control" id="namaBarang" v-model="barang.nama_barang" placeholder="Masukkan nama barang">
                  <!-- Validation -->
                  <div v-if="validation.nama_barang" class="mt-2 alert alert-danger">
                    {{ validation.nama_barang[0] }}
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
            const barang = reactive({
                nama_barang: "",
                id:0,
                id_kelompok:0,
            })
            let kelompoks = ref([]);

            //state validation
            const validation = ref([]);
            onMounted(() => {
              //get API from Laravel Backend
              axios
                .get("http://149.28.137.82/api/kelompoks")
                .then((response) => {
                  //assign state posts with response data
                  kelompoks.value = response.data.data;
                })
                .catch((error) => {
                  console.log(error.response.data);
                });
            });
            onMounted(() =>{

                axios
                .get(`http://149.28.137.82/api/master_atks/${route.params.id}`)
                .then((response) => {
                //assign state posts with response data
                barang.nama_barang=response.data.data.nama_barang,
                barang.id_kelompok=response.data.data.id_kelompok,
                barang.id=response.data.data.id
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
                let nama_barang = barang.nama_barang;
                let id_kelompok = barang.id_kelompok;

                //send server with axios
                axios.put(`http://149.28.137.82/api/updateMaster/${route.params.id}`, {
                        nama_barang: nama_barang,
                        id_kelompok: id_kelompok,
                })
                .then(() => {

                    //redirect ke halaman login
                    return router.push({
                        name: 'barang.index'
                    })

                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                token,
                barang,
                validation,
                route,
                kelompoks,
                logout,
                update
            }

        }

    }
</script>