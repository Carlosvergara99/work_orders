<template>
  <div>
    <Menu />

    <v-app id="inspire">
      <v-container fluid >
        <v-toolbar flat>
          <v-toolbar-title>Operadores</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog"  max-width="600px">
            <template v-slot:activator="{ on, attrs }">
              <!-- <v-container fluid> -->
              <v-btn
                fab
                dark
                small
                color="primary"
                class="mb-2"
                v-bind="attrs"
                v-on="on"
              >
                <v-icon dark> mdi-plus </v-icon>
              </v-btn>

              <!-- </v-container> -->
            </template>

            <v-card>
              <v-card-title>
                <span class="text-h5">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        v-model="form.name"
                        label="Nombre"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="form.code"
                        label="Código"
                        type="number"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="form.cc"
                        type="number"
                        label="Identificación"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="save"> Save </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </v-container>

      <v-container fluid>
        <v-data-table
          :headers="headers"
          :items="data"
          hide-default-footer
          class="elevation-1"
        >
          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" @click="editItem(item)">
              mdi-pencil
            </v-icon>
            <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
          </template>
        </v-data-table>
      </v-container>

      <v-pagination
        v-model="pagination.current"
        :length="pagination.total"
        @input="onPageChange"
      >
      </v-pagination>

          <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Esta seguro de Eliminar el operador ?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
    </v-app>
  </div>
</template>

<script>
import Menu from "../menu";
export default {
  components: {
    Menu,
  },

  data() {
    return {
      dialog: false,
      dialogDelete: false,
      headers: [
        { text: "Id", value: "id" },
        { text: "Nombres", value: "name" },

        { text: "Código", value: "code" },
        { text: "Identificación", value: "cc" },
        {
          text: "Actions",
          value: "actions",
          sortable: false,
          align: "center",
        },
      ],

      data: [],
      pagination: {
        current: 1,
        total: 0,
      },
      form: {
        name: "",
        cc: "",
        code: "",
        id: "",
      },
      editedIndex: -1,
      errors: [],
      dataDelete:''
    };
  },
  mounted() {
    this.getOperators();
  },
  methods: {
    getOperators() {       
      axios.post("api/operator/get?page=" + this.pagination.current)
        .then((response) => {
          this.data = response.data.data;
          this.pagination.current = response.data.current_page;
          this.pagination.total = response.data.last_page;
        })
        .catch((errors) => {});
    },
    onPageChange() {
      this.getOperators();
    },
    ValidateData(){
           const pattern = new RegExp('^[A-Z]+$', 'i');

      if (this.form.name == "" || !pattern.test(this.form.name) || /[^A-Za-z\d]/.test(this.form.name)) {
         toast.fire({
               icon: 'error',title: 'el campo nombre es obligatorio y valido'
           })
      }else if(!Number.parseInt(this.form.cc)){
          toast.fire({
                    icon: 'error',title: 'el campo Identificación es obligatorio y debe ser numerico'
           })
      }else if(!Number.parseInt(this.form.code)){
        toast.fire({
                 icon: 'error',title: 'el campo Código es obligatorio y debe ser numerico'
           })
      } else {
          this.validateFrom= true
      }
    },
    createOperator() {
      this.ValidateData()
      if (this.validateFrom) {
        axios.post("api/operator/create", this.form)
          .then((response) => {
             this.close()
             this.getOperators();
             toast.fire({
               icon: 'success',title: 'la información se ha guardado exitosamente'
             })
           
          })
          .catch((errors) => {
            if(errors.response.status ===422){
                 toast.fire({
                    icon: 'error',
                    title: errors.response.data.data
               })
            }
          });
      }
      
    },
    updateOperator() {
       this.ValidateData()
        axios.post("api/operator/update", this.form)
              .then((response) => {
                 this.getOperators() 
                 this.close()
                  toast.fire({
               icon: 'success',title: 'la información se ha actualizado exitosamente'
             })
          }).catch((errors) => {

               if(errors.response.status ===422){
                 toast.fire({
                    icon: 'error',
                    title: errors.response.data.data
               })
            }
            
          });
          
       },
    save() {
      if (this.editedIndex > -1) {
        this.updateOperator();
      } else {
        this.createOperator();
      }
   //   this.close();
    },
    deleteItem(item){
        this.dialogDelete=true
        this.dataDelete= item.id
    },
    close() {
        this.dialog = false;
           this.$nextTick(() => {
              this.editedIndex = -1
               this.form= {
                id: '',
                name:'',
                code:'',
                cc:''
            }
        })
         
      
    },
    editItem(item){
      this.editedIndex= this.data.indexOf(item)
      const data ={
          'id':item.id,
          'name' :item.name,
          'code':item.code,
          'cc' :item.cc
        }
      this.form =data
     this.dialog = true
    },
     closeDelete(){
      this.dialogDelete=false
      this.dataDelete =''
    },
    deleteItemConfirm(){
       const value={
         'id':this.dataDelete
       }
       axios.post("api/operator/delete",value)
        .then((response) => {
          this.closeDelete()
            toast.fire({
               icon: 'success',title: 'el operador ha eliminado exitosamente'
             })
           this.getOperators()
          
        })
        .catch((errors) => {
            if(errors.response.status ===422){
                 toast.fire({
                    icon: 'error',
                     title: 'no se puede eliminar el operador porque ya se encuentra asignado a una orden '
                 });
            }
           })
       }
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Crear Operador" : "Editar Operador";
    }
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },
};
</script>
