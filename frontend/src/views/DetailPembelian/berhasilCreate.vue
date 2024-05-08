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
                <h5 class="card-title">Detail Penjualan</h5>
                <hr>
                    <div class="alert alert-success mb-3">
                        Tambah berhasil.
                    </div>
                    <!-- Router link in bottom right corner -->
                    <div>
                      <router-link :to="{ name: 'detail.index', params: { id: route.params.id } }" class="btn btn-primary btn-lg mb-3">
                        OKE
                    </router-link>
                    </div>
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
    import { } from "vue";
    import { useRouter, useRoute } from "vue-router";
    export default {
      setup() {
        const token = localStorage.getItem('token')
        const router = useRouter();
        const route = useRoute()
        //reactive state
        //mounted
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
          logout,
          route,
        };
      },
    };
    </script>
    <style></style>