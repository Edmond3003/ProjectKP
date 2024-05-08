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
                <li @click.prevent="logout" class="list-group-item list-group-item-action" style="cursor:pointer">LOGOUT</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">TAMBAH CABANG</h4>
              <hr>
              <form @submit.prevent="store">
                <div class="form-group mb-3">
                  <label for="namaCabang" class="form-label">Nama Cabang</label>
                  <input
                    type="text"
                    class="form-control"
                    id="namaCabang"
                    v-model="cabang.nama_cabang"
                    placeholder="Masukkan nama cabang"
                  />
                  <!-- Validation -->
                  <div v-if="validation.nama_cabang" class="mt-2 alert alert-danger">
                    {{ validation.nama_cabang[0] }}
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="alamatCab" class="form-label">Alamat Cabang</label>
                  <input
                    type="text"
                    class="form-control"
                    id="alamatCab"
                    v-model="cabang.alamat_cab"
                    placeholder="Masukkan alamat"
                  />
                  <!-- Validation -->
                  <div v-if="validation.alamat_cab" class="mt-2 alert alert-danger">
                    {{ validation.alamat_cab[0] }}
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">SIMPAN</button>
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
  import { reactive, ref } from "vue";
  import { useRouter } from "vue-router";
  import axios from "axios";
  export default {
    setup() {
      const token = localStorage.getItem('token')
      //state departemen
      const cabang = reactive({
        nama_cabang: "",
        alamat_cab: "",
      });
      //state validation
      const validation = ref([]);
      //vue router
      const router = useRouter();
      //method store
      function store() {
        let nama_cabang = cabang.nama_cabang;
        let alamat_cab = cabang.alamat_cab;
        axios
          .post("http://149.28.137.82/api/cabangs", {
            nama_cabang: nama_cabang,
            alamat_cab: alamat_cab,
          })
          .then(() => {
            //redirect ke post index
            router.push({
              name: "cabang.index",
            });
          })
          .catch((error) => {
            //assign state validation with error
            validation.value = error.response.data;
          });
      }
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
      //return
      return {
        cabang,
        validation,
        router,
        logout,
        store,
      };
    },
  };
  </script>
  <style></style>
  ./createPage.vue