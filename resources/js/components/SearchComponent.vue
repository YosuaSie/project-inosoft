<template>

    <div class="container mt-5">
        <h2>Search Pipe Inventory:</h2><br>
        
        <div class="row justify-content-center">
          <!-- Dropdown untuk Product -->
          <div class="col-md-3">
              <label for="product">Product Type:</label>
              <select id="product" v-model="selectedProduct" @change="onProductChange" class="form-control">
                <option value="">All Type</option>
                <option v-for="product in uniqueProducts" :key="product.id" :value="product.type">
                    {{ product.type }} - {{ countType[product.type] }}
                </option>
              </select>
          </div>
      
          <!-- Dropdown untuk Grade -->
          <div class="col-md-3">
              <label for="grade">Grade:</label>
              <select id="grade" v-model="selectedGrade" @change="onGradeChange" class="form-control">
                <option value="">All Grade</option>
                <option v-for="product in uniqueGrades" :key="product.id" :value="product.grade">
                    {{ product.grade }} - {{ countGrade[product.grade] }}
                </option>
              </select>
          </div>
      
          <!-- Dropdown untuk Size -->
          <div class="col-md-3">
              <label for="size">Size (OD):</label>
              <select id="size" v-model="selectedSize" @change="onSizeChange" class="form-control">
                <option value="">All Size</option>
                <option v-for="product in uniqueSizes" :key="product.id" :value="product.size">
                    {{ product.size }} - {{ countSize[product.size] }}
                </option>
              </select>
          </div>
      
          <!-- Dropdown untuk Connection -->
          <div class="col-md-3">
              <label for="connection">Connection:</label>
              <select id="connection" v-model="selectedConnection" class="form-control">
                <option value="">All Connection</option>
                <option v-for="product in uniqueConnections" :key="product.id" :value="product.connection">
                    {{ product.connection }} - {{ countConnection[product.connection] }}
                </option>
              </select>
          </div>
        </div>
      
        <div class="row justify-content-center mt-3">
          <div class="d-grid gap-2 col-6 mx-auto">
            <!-- Tombol Cari -->
            <button @click="search" class="btn btn-primary">Find</button>
          </div>
          <div class="d-grid gap-2 col-6 mx-auto">
            <!-- Tombol Reset -->
            <button @click="reset" class="btn btn-warning">Reset</button>
          </div>
        </div>

        <br><br>
        <!-- Loading Indicator -->
        <div v-if="isLoading" class="loading-indicator">
          <div class="loading-spinner"></div>
          <h3>Loading data...</h3>
        </div>

        <!-- Tabel untuk menampilkan data yang difilter -->
        <table v-if="showTable" class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Code</th>
              <th>Qty</th>
              <th>Qty Unit</th>
              <th>Location</th>
              <th>Item Code</th>
              <th>Description</th>
              <th>Product Type</th>
              <th>Grade</th>
              <th>Size</th>
              <th>Connection</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in findProducts" :key="product.id">
              <td>{{ product.id }}</td>
              <td>{{ product.code }}</td>
              <td>{{ product.qty }}</td>
              <td>{{ product.qty_unit }}</td>
              <td>{{ product.country }}</td>
              <td>{{ product.item_code }}</td>
              <td>{{ product.desc }}</td>
              <td>{{ product.type }}</td>
              <td>{{ product.grade }}</td>
              <td>{{ product.size }}</td>
              <td>{{ product.connection }}</td>
            </tr>
          </tbody>
        </table>
        
    </div>
</template>
  
<script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        products: [],

        //variabel untuk menyimpan jumlah
        countType: {},
        countGrade: {},
        countSize: {},
        countConnection: {},
        
        //variabel untuk product yang terpilih
        selectedProduct: null,
        selectedGrade: null,
        selectedSize: null,
        selectedConnection: null,

        //variabel untuk menyimpan product yang unik (menghindari data kembar)
        uniqueProducts: [],
        uniqueGrades: [],
        uniqueSizes: [],
        uniqueConnections: [],
        
        //variabel untuk menyimpan product yang sudah di filter
        filteredGrades: [],
        filteredSizes: [],
        filteredConnections: [],

        //variabel untuk simpan data yang mau dicari
        findProducts: [],

        //boolean untuk tampilkan loading animation
        isLoading: false,

        //boolean untuk tampilkan table
        showTable: false,
      };
    },
    methods: {
      // Ambil data saat komponen dipasang
      async fetchData() {
        try {
            const response = await axios.get('/api/pipes'); // Ambil data dari API Laravel
            this.products = response.data.products;

            //cek type produk supaya tampil tidak kembar
            const unique = new Set(); // Menggunakan Set untuk menyimpan nama unik

            //load semua data
            this.products.forEach(product => {
              this.countType[product.type] = (this.countType[product.type] || 0) + 1;
              if (!unique.has(product.type)) {
                unique.add(product.type); // Tambahkan nama ke Set
                this.uniqueProducts.push(product); // Tambahkan produk ke array unik
              }

              this.countGrade[product.grade] = (this.countGrade[product.grade] || 0) + 1;
              if (!unique.has(product.grade)) {
                unique.add(product.grade); // Tambahkan nama ke Set
                this.uniqueGrades.push(product); // Tambahkan produk ke array unik
              }

              this.countSize[product.size] = (this.countSize[product.size] || 0) + 1;
              if (!unique.has(product.size)) {
                unique.add(product.size); // Tambahkan nama ke Set
                this.uniqueSizes.push(product); // Tambahkan produk ke array unik
              }

              this.countConnection[product.connection] = (this.countConnection[product.connection] || 0) + 1;
              if (!unique.has(product.connection)) {
                unique.add(product.connection); // Tambahkan nama ke Set
                this.uniqueConnections.push(product); // Tambahkan produk ke array unik
              }
            });
            
        } catch (error) {
            console.error('Error fetching data:', error);
        }
      },
      
      // Filter Grade berdasarkan Product yang dipilih
      onProductChange() {
        this.filteredGrades = this.products.filter(product => product.type === this.selectedProduct); //filter pertama dari tipe produk
        // console.log(this.selectedProduct + '-' + this.selectedGrade + '-' + this.selectedSize + '-' + this.selectedConnection);

        //cek grade produk supaya tampil tidak kembar
        const unique = new Set(); // Menggunakan Set untuk menyimpan nama unik
        this.uniqueGrades = []; // kosongkan supaya data tidak menumpuk
        this.uniqueSizes = [];
        this.uniqueConnections = [];
        this.countGrade = {}; // reset count supaya tidak menumpuk
        this.countSize = {};
        this.countConnection = {};

        this.filteredGrades.forEach(product => {

          //munculkan untuk filter grade
          this.countGrade[product.grade] = (this.countGrade[product.grade] || 0) + 1;
          if (!unique.has(product.grade)) {
            unique.add(product.grade); // Tambahkan nama ke Set
            this.uniqueGrades.push(product); // Tambahkan produk ke array unik
          }

          //munculkan untuk filter size
          this.countSize[product.size] = (this.countSize[product.size] || 0) + 1;
          if (!unique.has(product.size)) {
            unique.add(product.size); // Tambahkan nama ke Set
            this.uniqueSizes.push(product); // Tambahkan produk ke array unik
          }

          //munculkan untuk filter connection
          this.countConnection[product.connection] = (this.countConnection[product.connection] || 0) + 1;
          if (!unique.has(product.connection)) {
            unique.add(product.connection); // Tambahkan nama ke Set
            this.uniqueConnections.push(product); // Tambahkan produk ke array unik
          }
        });

        // Reset pilihan
        this.selectedGrade = "";  
        this.selectedSize = "";
        this.selectedConnection = "";
        this.filteredSizes = [];
        this.filteredConnections = [];
      },
  
      // Filter Size berdasarkan Grade yang dipilih
      onGradeChange() {
        // console.log(this.selectedProduct + '-' + this.selectedGrade + '-' + this.selectedSize + '-' + this.selectedConnection);

        //filter kedua dari grade produk -- jika tipe produk sudah dipilih maka pakai filteredGrades, tapi jika blm pilih pakai produk keseluruhan
        if(this.selectedProduct !== "") {
          this.filteredSizes = this.filteredGrades.filter(product => product.grade === this.selectedGrade); 
        }
        else {
          this.filteredSizes = this.products.filter(product => product.grade === this.selectedGrade);
        }
        

        //cek size produk supaya tampil tidak kembar
        const unique = new Set(); // Menggunakan Set untuk menyimpan nama unik
        this.uniqueSizes = []; // kosongkan supaya data tidak menumpuk
        this.uniqueConnections = [];
        this.countSize = {}; // reset count supaya tidak menumpuk
        this.countConnection = {};

        this.filteredSizes.forEach(product => {

          //munculkan untuk filter size
          this.countSize[product.size] = (this.countSize[product.size] || 0) + 1;
          if (!unique.has(product.size)) {
            unique.add(product.size); // Tambahkan nama ke Set
            this.uniqueSizes.push(product); // Tambahkan produk ke array unik
          }

          //munculkan untuk filter connection
          this.countConnection[product.connection] = (this.countConnection[product.connection] || 0) + 1;
          if (!unique.has(product.connection)) {
            unique.add(product.connection); // Tambahkan nama ke Set
            this.uniqueConnections.push(product); // Tambahkan produk ke array unik
          }
        });

        this.selectedSize = "";  // Reset pilihan
        this.selectedConnection = "";
        this.filteredConnections = [];
      },
  
      // Filter Connection berdasarkan Size yang dipilih
      onSizeChange() {

        //filter ketiga dari size produk
        if (this.selectedGrade !== "") {
          this.filteredConnections = this.filteredSizes.filter(product => product.size === this.selectedSize);
        }
        else if (this.selectedGrade == "" && this.selectedProduct !== "") {
          this.filteredConnections = this.filteredGrades.filter(product => product.size === this.selectedSize);
        }
        else if (this.selectedGrade == "" && this.selectedProduct == "") {
          this.filteredConnections = this.products.filter(product => product.size === this.selectedSize);
        }

        //cek connection produk supaya tampil tidak kembar
        const unique = new Set(); // Menggunakan Set untuk menyimpan nama unik
        this.uniqueConnections = []; // kosongkan supaya data tidak menumpuk
        this.countConnection = {}; // reset count supaya tidak menumpuk

        this.filteredConnections.forEach(product => {
          this.countConnection[product.connection] = (this.countConnection[product.connection] || 0) + 1;
          if (!unique.has(product.connection)) {
            unique.add(product.connection); // Tambahkan nama ke Set
            this.uniqueConnections.push(product); // Tambahkan produk ke array unik
          }
        });

        this.selectedConnection = "";  // Reset pilihan
      },
  
      // Aksi pencarian
      search() {
        this.showTable = true; //tampilkan table
        this.isLoading = true; //mulai proses loading

        //munculkan data table
        setTimeout(() => {
          this.findProducts = this.products.filter(product => {
            return (
              (!this.selectedProduct || product.type === this.selectedProduct) && 
              (!this.selectedGrade || product.grade === this.selectedGrade) && 
              (!this.selectedSize || product.size === this.selectedSize) && 
              (!this.selectedConnection || product.connection === this.selectedConnection)
            );
          });

          this.isLoading = false;
        }, 1000);
        
        // console.log('Filtered Products (as JSON):', JSON.stringify(this.filtered, null, 2));
      },
      reset() {
        //kosongkan semua
        this.selectedProduct = "";
        this.selectedGrade= "";
        this.selectedSize = "";
        this.selectedConnection = "";
        this.countType = {};
        this.countGrade = {};
        this.countSize = {};
        this.countConnection = {};
        this.uniqueProducts = [];
        this.uniqueGrades = [];
        this.uniqueSizes = [];
        this.uniqueConnections = [];

        //reset
        this.fetchData();
      }
    },
    mounted() {
      this.fetchData(); // Panggil API saat komponen dimuat

      //set nilai awal
      this.selectedProduct = "";
      this.selectedGrade= "";
      this.selectedSize = "";
      this.selectedConnection = "";
    }
  };
</script>
  
<style scoped>
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    font-weight: bold;
  }

  .loading-indicator {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .loading-spinner {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
  }
</style>
  