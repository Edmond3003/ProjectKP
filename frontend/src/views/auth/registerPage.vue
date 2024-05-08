<template>
    <div class="background">
      <div class="container-fluid mt-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card border-0 rounded shadow">
              <div class="card-body">
                <h4 class="text-center mb-4">REGISTER</h4>
                <form @submit.prevent="register">
                  <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" v-model="user.name" class="form-control" placeholder="Full Name">
                    <div v-if="validation.name" class="mt-2 alert alert-danger">
                      {{ validation.name[0] }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Email address</label>
                    <input type="email" v-model="user.email" class="form-control" placeholder="Email Address">
                    <div v-if="validation.email" class="mt-2 alert alert-danger">
                      {{ validation.email[0] }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" v-model="user.password" class="form-control" placeholder="Password">
                    <div v-if="validation.password" class="mt-2 alert alert-danger">
                      {{ validation.password[0] }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" v-model="user.password_confirmation" class="form-control"
                      placeholder="Confirm Password">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block mt-3">REGISTER</button>
                    <router-link to="/login" class="btn btn-secondary btn-block mt-3">Already have an account? Login</router-link>
                  </div>
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
    align-items: center;
  }
  
  .card {
    background-color: rgba(255, 255, 255, 0.8);
  }
  
  .card-body {
    padding: 40px;
  }
  
  .btn-block {
    width: 100%;
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
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            })

            //state validation
            const validation = ref([])

            //method register
            function register() {

                //define variable 
                let name = user.name
                let email = user.email
                let password = user.password
                let password_confirmation = user.password_confirmation

                //send server with axios
                axios.post('http://149.28.137.82/api/register', {
                        name,
                        email,
                        password,
                        password_confirmation
                })
                .then(() => {

                    //redirect ke halaman login
                    return router.push({
                        name: 'login'
                    })

                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                user, // <-- state user
                validation, // <-- state validation 
                register // <-- method register
            }

        }

    }
</script>