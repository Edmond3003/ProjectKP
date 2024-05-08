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
              <h4 class="card-title">TAMBAH KELOMPOK</h4>
              <hr>
              <form @submit.prevent="store">
                <div class="form-group mb-3">
                  <label for="namaKelompok" class="form-label">Nama Kelompok</label>
                  <input
                    type="text"
                    class="form-control"
                    id="namaKelompok"
                    v-model="kelompok.nama_kelompok"
                    placeholder="Masukkan nama kelompok"
                  />
                  <!-- Validation -->
                  <div v-if="validation.nama_kelompok" class="mt-2 alert alert-danger">
                    {{ validation.nama_kelompok[0] }}
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
      const kelompok = reactive({
        nama_kelompok: "",
      });
      //state validation
      const validation = ref([]);
      //vue router
      const router = useRouter();
      //method store
      function store() {
        let nama_kelompok = kelompok.nama_kelompok;
        axios
          .post("http://149.28.137.82/api/kelompoks", {
            nama_kelompok: nama_kelompok,
          })
          .then(() => {
            //redirect ke post index
            router.push({
              name: "kelompok.index",
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
        kelompok,
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