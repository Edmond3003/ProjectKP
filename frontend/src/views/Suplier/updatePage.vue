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
                <div class="mb-3">
                <label for="idKelompok" class="form-label">ID</label>
                <input type="text" class="form-control" id="idSuplier" v-model="suplier.id" placeholder="Masukkan nama suplier" :disabled="true">
              </div>
                <div class="mb-3">
                  <label for="namaSuplier" class="form-label">Nama Suplier</label>
                  <input type="text" class="form-control" id="namaSuplier" v-model="suplier.nama_suplier" placeholder="Masukkan nama suplier">
                  <!-- Validation -->
                  <div v-if="validation.nama_suplier" class="mt-2 alert alert-danger">
                    {{ validation.nama_suplier[0] }}
                  </div>
                </div>
                <div class="mb-3">
                  <label for="alamat" class="form-label">Alamat</label>
                  <input type="text" class="form-control" id="alamat" v-model="suplier.alamat" placeholder="Masukkan alamat">
                  <!-- Validation -->
                  <div v-if="validation.alamat" class="mt-2 alert alert-danger">
                    {{ validation.alamat[0] }}
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
            const suplier = reactive({
                nama_suplier: "",
                id:0,
                alamat:"",
            })

            //state validation
            const validation = ref([])
            onMounted(() =>{

                axios
                .get(`http://149.28.137.82/api/supliers/${route.params.id}`)
                .then((response) => {
                //assign state posts with response data
                suplier.alamat=response.data.data.alamat,
                suplier.nama_suplier=response.data.data.nama_suplier,
                suplier.id=response.data.data.id
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
                let nama_suplier = suplier.nama_suplier;
                let alamat = suplier.alamat;

                //send server with axios
                axios.put(`http://149.28.137.82/api/supliers/${route.params.id}`, {
                        nama_suplier: nama_suplier,
                        alamat: alamat,
                })
                .then(() => {

                    //redirect ke halaman login
                    return router.push({
                        name: 'suplier.index'
                    })

                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                token,
                suplier,
                validation,
                route,
                logout,
                update
            }

        }

    }
</script>