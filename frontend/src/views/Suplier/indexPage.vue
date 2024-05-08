<template>
  <div class="background-image">
    <div class="container-fluid mt-5">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">MAIN MENU</h5>
              <hr>
              <ul class="list-group">
                <router-link :to="{ name: 'barang.index' }" class="list-group-item list-group-item-action">
                  MASTER ATK
                </router-link>
                <router-link :to="{ name: 'kelompok.index' }" class="list-group-item list-group-item-action">KELOMPOK</router-link>
                <router-link :to="{ name: 'suplier.index' }" class="list-group-item list-group-item-action">SUPLIER</router-link>
                <router-link :to="{ name: 'cabang.index' }" class="list-group-item list-group-item-action">CABANG</router-link>
                <router-link :to="{ name: 'pembelian.index' }" class="list-group-item list-group-item-action">PEMBELIAN</router-link>
                <router-link :to="{ name: 'penjualan.index' }" class="list-group-item list-group-item-action">PENJUALAN</router-link>
                <router-link :to="{ name: 'laporanMS' }" class="list-group-item list-group-item-action">LAPORAN HARIAN</router-link>
                <li @click.prevent="logout" class="list-group-item list-group-item-action" style="cursor: pointer;">
                  LOGOUT
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Suplier</h5>
              <hr>
              <router-link :to="{ name: 'suplier.create' }" class="btn btn-success mb-3">TAMBAH SUPLIER</router-link>
              <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">NAMA SUPLIER</th>
                    <th scope="col">ALAMAT</th>
                    <th scope="col">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(suplier, id) in supliers" :key="id">
                    <td>{{ suplier.id }}</td>
                    <td>{{ suplier.nama_suplier }}</td>
                    <td>{{ suplier.alamat }}</td>
                    <td>
                      <router-link
                        :to="{ name: 'suplier.edit', params: { id: suplier.id } }"
                        class="btn btn-sm btn-primary mr-1" style="margin-right: 20px;">
                        EDIT
                      </router-link>
                      <button @click.prevent="suplierDelete(suplier.id)" class="btn btn-sm btn-danger ml-1">DELETE</button>
                    </td>
                  </tr>
                </tbody>
              </table>
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
  import axios from "axios";
  import { onMounted, ref } from "vue";
  import { useRouter } from "vue-router";
  export default {
    setup() {
      const token = localStorage.getItem('token')
      const router = useRouter();
      //reactive state
      let supliers = ref([]);
      //mounted
      onMounted(() => {
        //get API from Laravel Backend
        axios
          .get(`http://149.28.137.82/api/supliers`)
          .then((response) => {
            //assign state posts with response data
            supliers.value = response.data.data;
          })
          .catch((error) => {
            console.log(error.response.data);
          });
      });
      //method delete
      function suplierDelete(id) {
        //delete data post by ID
        axios
          .delete(`http://149.28.137.82/api/supliers/${id}`)
          .then(() => {
            // splice posts
            const index = this.supliers.findIndex(
              (suplier) => suplier.id === id
            );
            if (~index) {
              // if the post exists in array
              this.supliers.splice(index, 1);
            }
          })
          .catch((error) => {
            console.log(error.response.data);
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
        supliers,
        logout,
        suplierDelete,
      };
    },
  };
  </script>
  <style></style>