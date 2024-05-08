<template>
    <div class="background">
      <div class="container-fluid mt-5">
        <div class="row justify-content-center">
          <div class="col-md-4">
            <div v-if="loginFailed" class="alert alert-danger">
              Email atau Password Anda salah.
            </div>
            <div class="card border-0 rounded shadow">
              <div class="card-body">
                <h4>LOGIN</h4>
                <hr>
                <form @submit.prevent="login">
                  <div class="form-group">
                    <label>Email address</label>
                    <input type="email" v-model="user.email" class="form-control" placeholder="Email Address">
                  </div>
                  <div v-if="validation.email" class="mt-2 alert alert-danger">
                    {{ validation.email[0] }}
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" v-model="user.password" class="form-control" placeholder="Password">
                  </div>
                  <div v-if="validation.password" class="mt-2 alert alert-danger">
                    {{ validation.password[0] }}
                  </div>
                  <button type="submit" class="btn btn-primary btn-block mt-2">LOGIN</button>
                  <p>Don't have an account yet? <router-link :to="{ name: 'register' }">Please register</router-link></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <style>
  .background {
    background-image: url('https://lh3.googleusercontent.com/p/AF1QipPdzo50fo97Hr_Lh-LaI4fcuj3G1EGkpHsb9YDC=s1360-w1360-h1020');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.5); /* transparan */
  }
  .card {
    background-color: transparent; /* agar card tetap transparan */
  }
  .card-body {
    background-color: rgba(255, 255, 255, 0.8); /* warna card dengan transparansi */
  }
  .alert {
    border-radius: 0;
  }
  </style>

<script>
    import { reactive, ref } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'

    export default {

        setup() {

            //inisialisasi vue router on Composition API
            const router = useRouter()

            //state user
            const user = reactive({
                email: '',
                password: '',
            })

            //state validation
            const validation = ref([])

            //state loginFailed
            const loginFailed = ref(null)

            //method login
            function login() {

                //define variable 
                let email = user.email
                let password = user.password

                //send server with axios
                axios.post('http://149.28.137.82/api/login', {
                        email,
                        password,
                })
                .then(response => {

                    if(response.data.success) {

                        //set token
                        localStorage.setItem('token', response.data.token)

                        //redirect ke halaman dashboard
                        return router.push({
                            name: 'barang.index'
                        })
                    }

                    //set state loggedIn to true
                    loginFailed.value = true


                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                user,           // <-- state user
                validation,     // <-- state validation 
                loginFailed,    // <-- state loggedIn
                login           // <-- method login
            }

        }

    }
</script>