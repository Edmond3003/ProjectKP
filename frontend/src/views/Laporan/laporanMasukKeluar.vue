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
              <h5 class="card-title">Laporan</h5>
              <hr>
              <!-- Form input tanggal awal dan akhir -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="tanggal_awal">Tanggal Awal</label>
                  <input type="date" class="form-control" id="tanggal_awal" v-model="tanggalAwal">
                </div>
                <div class="form-group col-md-6">
                  <label for="tanggal_akhir">Tanggal Akhir</label>
                  <input type="date" class="form-control" id="tanggal_akhir" v-model="tanggalAkhir">
                </div>
              </div>
              <!-- Tombol untuk memuat ulang data laporan -->
              <button @click="loadLaporan" class="btn btn-primary mb-3">Tampilkan Laporan</button>
              
              <!-- Tabel untuk menampilkan data laporan -->
              <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Jumlah Masuk</th>
                    <th scope="col">Jumlah Keluar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(laporan, index) in dataLaporan" :key="index">
                    <td>{{ laporan.tanggal }}</td>
                    <td>{{ laporan.nama_barang }}</td>
                    <td>
                      <router-link :to="{ name: 'laporanHTglBarangBeli', params: { tanggal: laporan.tanggal, barang: laporan.nama_barang }}">
                        {{ laporan.jumlah_masuk }}
                      </router-link>
                    </td>
                    <td>
                      <router-link :to="{ name: 'laporanHTglBarangJual', params: { tanggal: laporan.tanggal, barang: laporan.nama_barang }}">
                        {{ laporan.jumlah_keluar }}
                      </router-link>
                    </td>
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
  import { ref } from "vue";
  import html2pdf from "html2pdf.js";
  import { useRouter } from "vue-router";
  
  export default {
    setup() {
      const token = localStorage.getItem('token');
      const router = useRouter();
      const tanggalAwal = ref('');
      const tanggalAkhir = ref('');
      const dataLaporan = ref([]);
  
      function loadLaporan() {
        axios.get(`http://149.28.137.82/api/laporan`, {
            params: {
              tanggal_awal: tanggalAwal.value,
              tanggal_akhir: tanggalAkhir.value
            }
          })
          .then(response => {
            // Ubah nilai null menjadi 0 untuk jumlah masuk dan jumlah keluar
            dataLaporan.value = response.data.map(item => ({
              ...item,
              jumlah_masuk: item.jumlah_masuk || 0,
              jumlah_keluar: item.jumlah_keluar || 0
            }));
          })
          .catch(error => {
            console.log(error.response.data);
          });
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
            <h1>Laporan Keluar Masuk ATK</h1>
            <h2>Tunasjaya Honda Motor Magelang</h2>
            <p>Tanggal: ${tanggalAwal.value} - ${tanggalAkhir.value}</p>
          </div>
        `;

        // Mendapatkan data laporan dalam bentuk tabel HTML
        const tableContent = `
          <table border="1" style="border-collapse: collapse; width: 100%; text-align: left; border: 1px solid #000;"
            <thead>
              <tr>
                <th scope="col" style="padding: 8px;">Tanggal</th>
                <th scope="col" style="padding: 8px;">Nama Barang</th>
                <th scope="col" style="padding: 8px;">Jumlah Masuk</th>
                <th scope="col" style="padding: 8px;">Jumlah Keluar</th>
              </tr>
            </thead>
            <tbody>
              ${dataLaporan.value.map(laporan => `
                <tr style="border: 1px solid #000;">
                  <td style="padding: 8px; border: 1px solid #000;">${laporan.tanggal}</td>
                  <td style="padding: 8px; border: 1px solid #000;">${laporan.nama_barang}</td>
                  <td style="padding: 8px; border: 1px solid #000;">${laporan.jumlah_masuk}</td>
                  <td style="padding: 8px; border: 1px solid #000;">${laporan.jumlah_keluar}</td>
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
  
      //return
      return {
        tanggalAwal,
        tanggalAkhir,
        dataLaporan,
        loadLaporan,
        logout,
        cetakPDF
      };
    },
  };
  </script>
    <style></style>