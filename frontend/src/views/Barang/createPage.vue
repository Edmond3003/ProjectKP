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
              <h4 class="card-title">TAMBAH BARANG</h4>
              <hr>
              <form @submit.prevent="store">
                <div class="form-group mb-3">
                  <label for="content" class="form-label">Kelompok</label>
                  <select class="form-control" v-model="barang.id_kelompok">
                    <option :value=0 disabled selected>Pilih Kelompok</option>
                    <option v-for="kelompok in kelompoks" :key="kelompok.id" :value="kelompok.id">
                      {{ kelompok.nama_kelompok }}
                    </option>
                  </select>
                  <!-- validation -->
                  <div v-if="validation.id_kelompok" class="mt-2 alert alert-danger">
                    {{ validation.id_kelompok[0] }}
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="namaBarang" class="form-label">Nama Barang</label>
                  <input
                    type="text"
                    class="form-control"
                    id="namaBarang"
                    v-model="barang.nama_barang"
                    placeholder="Masukkan nama barang"
                  />
                  <!-- Validation -->
                  <div v-if="validation.nama_barang" class="mt-2 alert alert-danger">
                    {{ validation.nama_barang[0] }}
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
  import { onMounted, reactive, ref } from "vue";
  import { useRouter } from "vue-router";
  import axios from "axios";
  export default {
    setup() {
      const token = localStorage.getItem('token')
      //state departemen
      const barang = reactive({
        nama_barang: "",
        id_kelompok : 0,
      });
      //state validation
      const validation = ref([]);
      //vue router
      const router = useRouter();
      let kelompoks = ref([]);
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
      //method store
      function store() {
        let nama_barang = barang.nama_barang;
        let id_kelompok = barang.id_kelompok;
        axios
          .post("http://149.28.137.82/api/master_atks", {
            nama_barang: nama_barang,
            id_kelompok: id_kelompok,
          })
          .then(() => {
            //redirect ke post index
            router.push({
              name: "barang.index",
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
        kelompoks,
        barang,
        validation,
        router,
        logout,
        store,
      };
    },
  };
  </script>
  <style></style>