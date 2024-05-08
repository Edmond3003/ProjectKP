//import vue router
import { createRouter, createWebHistory } from 'vue-router'

//define a routes
const routes = [
    {
        path: "/",
        name: "welcome",
        component: () => import("@/components/welcomeLayout.vue"),
        children: [
            {
                path: '/login',
                name: 'login',
                component: () => import('@/views/auth/loginPage.vue')
            },
            {
                path: '/register',
                name: 'register',
                component: () => import( '@/views/auth/registerPage.vue')
            },
        ]
      },
      {
        path: "/",
        name: "topbar",
        component: () => import("@/components/topBar.vue"),
        children: [
            {
                path: '/barangIndex',
                name: 'barang.index',
                component: () => import( '@/views/Barang/indexPage.vue')
            },
            {
                path: '/barangUpdate',
                name: 'barang.edit',
                component: () => import( '@/views/Barang/updatePage.vue')
            },
            {
                path: '/barangCreate',
                name: 'barang.create',
                component: () => import( '@/views/Barang/createPage.vue')
            },
            {
                path: '/kelompokIndex',
                name: 'kelompok.index',
                component: () => import( '@/views/Kelompok/indexPage.vue')
            },
            {
                path: '/kelompokUpdate',
                name: 'kelompok.edit',
                component: () => import( '@/views/Kelompok/updatePage.vue')
            },
            {
                path: '/kelompokCreate',
                name: 'kelompok.create',
                component: () => import( '@/views/Kelompok/createPage.vue')
            },
            {
                path: '/suplierIndex',
                name: 'suplier.index',
                component: () => import( '@/views/Suplier/indexPage.vue')
            },
            {
                path: '/suplierUpdate',
                name: 'suplier.edit',
                component: () => import( '@/views/Suplier/updatePage.vue')
            },
            {
                path: '/suplierCreate',
                name: 'suplier.create',
                component: () => import( '@/views/Suplier/createPage.vue')
            },
            {
                path: '/pembelianIndex',
                name: 'pembelian.index',
                component: () => import( '@/views/HeaderPembelian/indexPage.vue')
            },
            {
                path: '/pembelianUpdate',
                name: 'pembelian.edit',
                component: () => import( '@/views/HeaderPembelian/updatePage.vue')
            },
            {
                path: '/pembelianCreate',
                name: 'pembelian.create',
                component: () => import( '@/views/HeaderPembelian/createPage.vue')
            },
            {
                path: '/detailIndex',
                name: 'detail.index',
                component: () => import( '@/views/DetailPembelian/indexPage.vue')
            },
            {
                path: '/detailUpdate',
                name: 'detail.edit',
                component: () => import( '@/views/DetailPembelian/updatePage.vue')
            },
            {
                path: '/detailCreate',
                name: 'detail.create',
                component: () => import( '@/views/DetailPembelian/createPage.vue')
            },
            {
                path: '/verifBerhasil',
                name: 'verif',
                component: () => import( '@/views/HeaderPembelian/berhasilVerif.vue')
            },
            {
                path: '/updateBerhasil',
                name: 'updateSukses',
                component: () => import( '@/views/DetailPembelian/berhasilUpdate.vue')
            },
            {
                path: '/createBerhasil',
                name: 'createSukses',
                component: () => import( '@/views/DetailPembelian/berhasilCreate.vue')
            },
            {
                path: '/cabangIndex',
                name: 'cabang.index',
                component: () => import( '@/views/Cabang/indexPage.vue')
            },
            {
                path: '/cabangUpdate',
                name: 'cabang.edit',
                component: () => import( '@/views/Cabang/updatePage.vue')
            },
            {
                path: '/cabangCreate',
                name: 'cabang.create',
                component: () => import( '@/views/Cabang/createPage.vue')
            },
            {
                path: '/penjualanIndex',
                name: 'penjualan.index',
                component: () => import( '@/views/HeaderPenjualan/indexPage.vue')
            },
            {
                path: '/penjualanUpdate',
                name: 'penjualan.edit',
                component: () => import( '@/views/HeaderPenjualan/updatePage.vue')
            },
            {
                path: '/penjualanCreate',
                name: 'penjualan.create',
                component: () => import( '@/views/HeaderPenjualan/createPage.vue')
            },
            {
                path: '/verifjualBerhasil',
                name: 'verifjual',
                component: () => import( '@/views/HeaderPenjualan/berhasilVerif.vue')
            },
            {
                path: '/detailjualIndex',
                name: 'detailjual.index',
                component: () => import( '@/views/DetailPenjualan/indexPage.vue')
            },
            {
                path: '/detailjualUpdate',
                name: 'detailjual.edit',
                component: () => import( '@/views/DetailPenjualan/updatePage.vue')
            },
            {
                path: '/detailjualCreate',
                name: 'detailjual.create',
                component: () => import( '@/views/DetailPenjualan/createPage.vue')
            },
            {
                path: '/updatejualBerhasil',
                name: 'updatejualSukses',
                component: () => import( '@/views/DetailPenjualan/berhasilUpdate.vue')
            },
            {
                path: '/createjualBerhasil',
                name: 'createjualSukses',
                component: () => import( '@/views/DetailPenjualan/berhasilCreate.vue')
            },
            {
                path: '/laporanMS',
                name: 'laporanMS',
                component: () => import( '@/views/Laporan/laporanMasukKeluar.vue')
            },
            {
                path: '/laporanHTglBarangBeli',
                name: 'laporanHTglBarangBeli',
                component: () => import( '@/views/Laporan/laporanTanggalBarangBeli.vue')
            },
            {
                path: '/laporanHTglBarangJual',
                name: 'laporanHTglBarangJual',
                component: () => import( '@/views/Laporan/laporanTanggalBarangJual.vue')
            },
        ]
      },
]

//create router
const router = createRouter({
    history: createWebHistory(),
    routes  // config routes
})

export default router