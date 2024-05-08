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
                <h5 class="card-title">Laporan Barang Masuk</h5>
                <hr>
                <!-- Tabel untuk menampilkan data laporan -->
                <table class="table table-striped table-bordered">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Suplier</th>
                      <th scope="col">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(laporan, index) in dataLaporan" :key="index">
                      <td>{{ laporan.suplier }}</td>
                      <td>{{ laporan.qty }}</td>
                    </tr>
                  </tbody>
                </table>
                <button @click="cetakPDF" class="btn btn-primary">Cetak PDF</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import axios from "axios";
import { ref, onMounted } from "vue";
import html2pdf from "html2pdf.js";
import { useRouter, useRoute } from "vue-router";

export default {
  setup() {
    const router = useRouter();
    const route = useRoute();
    const dataLaporan = ref([]);
    
    const token = localStorage.getItem('token');

    onMounted(() => {
        axios
        .get("http://149.28.137.82/api/laporanByTglBarangBeli", {
          params: {
            tanggal: route.params.tanggal,
            nama_barang: route.params.barang
          },
        })
        .then((response) => {
          dataLaporan.value = response.data;
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    });

    function logout() {
        //logout
        axios.defaults.headers.common.Authorization = `Bearer ${token}`
        axios.post('http://149.28.137.82/api/logout')
          .then(response => {
            if (response.data.success) {
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

      function cetakPDF() {
        const opt = {
          margin: 1,
          filename: "laporan.pdf",
          image: { type: "jpeg", quality: 0.98 },
          html2canvas: { scale: 2 },
          jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
        };

        const headerContent = `
          <div style="text-align: center;">
            <h1>Laporan Masuk Per Tanggal & ATK</h1>
            <h2>Tunasjaya Honda Motor Magelang</h2>
            <p>Tanggal: ${route.params.tanggal}</p>
            <p>Alat Tulis: ${route.params.barang}</p>
          </div>
        `;

        // Mendapatkan data laporan dalam bentuk tabel HTML
        const tableContent = `
        <table border="1" style="border-collapse: collapse; width: 100%; text-align: left; border: 1px solid #000;">
          <thead>
            <tr>
              <th scope="col" style="padding: 8px;">Suplier</th>
              <th scope="col" style="padding: 8px;">Jumlah</th>
            </tr>
          </thead>
          <tbody>
            ${dataLaporan.value.map(laporan => `
              <tr style="border: 1px solid #000;">
                <td style="padding: 8px; border: 1px solid #000;">${laporan.suplier}</td>
                <td style="padding: 8px; border: 1px solid #000;">${laporan.qty}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
        `;

        // Menggabungkan header dan tabel ke dalam satu dokumen
        const content = headerContent + tableContent;

        // Membuat PDF
        html2pdf()
          .from(content)
          .set(opt)
          .save();
      }

    return {
      dataLaporan,
      logout,
      route,
      cetakPDF
    };
  },
};
</script>

  
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
  