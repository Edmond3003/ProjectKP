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
              <h5 class="card-title">Master ATK</h5>
              <hr>
              <router-link :to="{ name: 'barang.create' }" class="btn btn-success mb-3">TAMBAH BARANG</router-link>
              <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">NAMA BARANG</th>
                    <th scope="col">KELOMPOK</th>
                    <th scope="col">TOTAL</th>
                    <th scope="col">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(barang, id) in barangs" :key="id">
                    <td>{{ barang.id }}</td>
                    <td>{{ barang.nama_barang }}</td>
                    <td>{{ barang.kelompok.nama_kelompok }}</td>
                    <td>{{ barang.total }}</td>
                    <td>
                      <router-link
                        :to="{ name: 'barang.edit', params: { id: barang.id } }"
                        class="btn btn-sm btn-primary mr-1" style="margin-right: 20px;">
                        EDIT
                      </router-link>
                      <button @click.prevent="barangDelete(barang.id)" class="btn btn-sm btn-danger ml-1">DELETE</button>
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
      let barangs = ref([]);
      //mounted
      onMounted(() => {
        //get API from Laravel Backend
        axios
          .get(`http://149.28.137.82/api/master_atks`)
          .then((response) => {
            //assign state posts with response data
            barangs.value = response.data.data;
          })
          .catch((error) => {
            console.log(error.response.data);
          });
      });
      //method delete
      function barangDelete(id) {
        //delete data post by ID
        axios
          .delete(`http://149.28.137.82/api/master_atks/${id}`)
          .then(() => {
            // splice posts
            const index = this.barangs.findIndex(
              (barang) => barang.id === id
            );
            if (~index) {
              // if the post exists in array
              this.barangs.splice(index, 1);
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
        barangs,
        logout,
        barangDelete,
      };
    },
  };
  </script>
  <style></style>