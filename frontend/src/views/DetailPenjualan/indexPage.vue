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
              <form @submit.prevent="update">
                <div class="row">
                  <!-- Kolom pertama -->
                  <div class="col-md-6">
                    <div class="form-group mt-3">
                      <label for="id">ID Penjualan</label>
                      <input type="text" class="form-control" id="id" v-model="penjualan.id" disabled>
                    </div>
                    <div class="form-group mt-3">
                      <label for="suplier">Cabang</label>
                      <select class="form-control" id="suplier" v-model="penjualan.id_cabang">
                        <option v-for="cabang in cabangs" :key="cabang.id" :value="cabang.id">{{ cabang.nama_cabang }}</option>
                      </select>
                    </div>
                  </div>
                  <!-- Kolom kedua -->
                  <div class="col-md-6 mt-3">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <input type="text" class="form-control" id="status" v-model="penjualan.status" disabled>
                    </div>
                    <div class="form-group mt-3">
                      <label for="tgl">Tanggal</label>
                      <input type="date" class="form-control" id="tgl" v-model="penjualan.tgl">
                    </div>
                  </div>
                </div>
                <!-- Tombol update -->
                <button v-if="penjualan.status !== 1" type="submit" class="btn btn-primary mt-3">Update</button>
              </form>
              <h5 class="card-title mt-3">Detail Penjualan</h5>
              <hr>
              <router-link v-if="penjualan.status !== 1" :to="{ name: 'detailjual.create', params: { id: route.params.id } }" class="btn btn-success mb-3">TAMBAH DETAIL PENJUALAN</router-link>
              <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">HDR PN</th>
                    <th scope="col">NAMA BARANG</th>
                    <th scope="col">KUANTITAS</th>
                    <th scope="col">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(detail, id) in details" :key="id">
                    <td>{{ detail.id }}</td>
                    <td>{{ detail.hdr_pn }}</td>
                    <td>{{ detail.masteratk.nama_barang }}</td>
                    <td>{{ detail.qty }}</td>
                    <td>
                      <router-link
                        v-if="penjualan.status !== 1"
                        :to="{ name: 'detailjual.edit', params: { id_detail: detail.id, id:route.params.id } }"
                        class="btn btn-sm btn-primary mr-1" style="margin-right: 20px;">
                        EDIT
                      </router-link>
                      <button v-if="penjualan.status !== 1" @click.prevent="barangDelete(detail.id)" class="btn btn-sm btn-danger ml-1">DELETE</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <router-link :to="{ name: 'penjualan.index' }" class="btn btn-secondary mt-3 mr-2">
                Kembali
              </router-link>
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
  import { onMounted, reactive, ref } from "vue";
  import { useRouter, useRoute } from "vue-router";
  export default {
    setup() {
      const token = localStorage.getItem('token')
      const router = useRouter();
      const route = useRoute()
      const validation = ref([]);
      //reactive state
      let details = ref([]);
      let id_header = route.params.id;
      const penjualan = reactive({
        tgl: '',
        id:0,
        id_cabang:0,
        status: "",
      });
      let cabangs = ref([]);
      //mounted
      onMounted(() => {
        //get API from Laravel Backend
        axios
          .get(`http://149.28.137.82/api/showDetailjual/${route.params.id}`)
          .then((response) => {
            //assign state posts with response data
            details.value = response.data.data;
          })
          .catch((error) => {
            console.log(error.response.data);
          });
      });
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
          penjualan.id=response.data.data.id,
          penjualan.status=response.data.data.status
        })
          .catch((error) => {
          console.log(error.response.data);
        });
      })
      //method delete
      function barangDelete(id) {
        //delete data post by ID
        axios
          .delete(`http://149.28.137.82/api/detailjuals/${id}`)
          .then(() => {
            // splice posts
            const index = this.details.findIndex(
              (detail) => detail.id === id
            );
            if (~index) {
              // if the post exists in array
              this.details.splice(index, 1);
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
              name: 'updatejualSukses',
              params: {
                id: route.params.id  // Menambahkan route.params.id ke dalam objek params
              }
          })

      }).catch(error => {
          //set validation dari error response
          validation.value = error.response.data
      })

  }
      //return
      return {
        details,
        penjualan,
        cabangs,
        id_header,
        logout,
        barangDelete,
        update,
        route,
      };
    },
  };
  </script>
  <style></style>