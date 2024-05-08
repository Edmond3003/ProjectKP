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
                  <label for="content" class="form-label">Barang</label>
                  <select class="form-control" v-model="detail.id_barang">
                    <option selected disabled value="">PILIH BARANG</option>
                    <option v-for="barang in barangs" :key="barang.id" :value="barang.id">
                      {{ barang.nama_barang }}
                    </option>
                  </select>
                  <!-- validation -->
                  <div v-if="validation.id_barang" class="mt-2 alert alert-danger">
                    {{ validation.id_barang[0] }}
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="content" class="form-label">ID Header</label>
                  <select class="form-control" v-model="detail.hdr_pm">
                    <option selected disabled value="">PILIH ID HEADER</option>
                    <option v-for="header in headers" :key="header.id" :value="header.id">
                      {{ header.id }}
                    </option>
                  </select>
                  <!-- validation -->
                  <div v-if="validation.hdr_pm" class="mt-2 alert alert-danger">
                    {{ validation.hdr_pm[0] }}
                  </div>
                </div>
              <div class="mb-3">
                <label for="idDetail" class="form-label">ID</label>
                <input type="text" class="form-control" id="idDetail" v-model="detail.id" placeholder="Masukkan id" :disabled="true">
              </div>
                <div class="mb-3">
                  <label for="kuantiti" class="form-label">Kuantitas</label>
                  <input type="number" class="form-control" id="kuantiti" v-model="detail.qty" placeholder="Masukkan kuantitas">
                  <!-- Validation -->
                  <div v-if="validation.qty" class="mt-2 alert alert-danger">
                    {{ validation.qty[0] }}
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
            const detail = reactive({
                qty: 0,
                id_barang:0,
                hdr_pm:0,
                id: 0,
            })
            let barangs = ref([]);
            let headers = ref([]);
            //state validation
            const validation = ref([]);
            onMounted(() => {
              //get API from Laravel Backend
              axios
                .get("http://149.28.137.82/api/master_atks")
                .then((response) => {
                  //assign state posts with response data
                  barangs.value = response.data.data;
                })
                .catch((error) => {
                  console.log(error.response.data);
                });
            });
            onMounted(() => {
              //get API from Laravel Backend
              axios
                .get("http://149.28.137.82/api/cariVerif")
                .then((response) => {
                  //assign state posts with response data
                  headers.value = response.data.data;
                })
                .catch((error) => {
                  console.log(error.response.data);
                });
            });
            onMounted(() =>{

                axios
                .get(`http://149.28.137.82/api/details/${route.params.id_detail}`)
                .then((response) => {
                //assign state posts with response data
                detail.qty=response.data.data.qty,
                detail.id_barang=response.data.data.id_barang,
                detail.hdr_pm=response.data.data.hdr_pm,
                detail.id=response.data.data.id
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
                let id_barang = detail.id_barang
                let hdr_pm = detail.hdr_pm
                let qty = detail.qty

                //send server with axios
                axios.put(`http://149.28.137.82/api/details/${route.params.id_detail}`, {
                        id_barang: id_barang,
                        hdr_pm: hdr_pm,
                        qty: qty
                })
                .then(() => {

                    //redirect ke halaman login
                    return router.push({
                        name: 'updateSukses',
                        params: {
                        id: route.params.id  // Menambahkan route.params.id ke dalam objek params
                      }
                    })

                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                token,
                detail,
                validation,
                route,
                headers,
                barangs,
                logout,
                update
            }

        }

    }
</script>