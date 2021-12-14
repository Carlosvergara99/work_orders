<template>
  <div>
 <v-btn
          color="primary"
          class="ma-2"
          dark
          small
          @click="dialog3 = true"
        >
                        <v-icon dark> mdi-plus </v-icon>

          Ordenes
        </v-btn>
        <v-dialog
          v-model="dialog3"
          max-width="500px"
        >
          <v-card>
            <v-card-title>
              Crear Ordenes de trabajo 
            </v-card-title>
           <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        v-model="from.order_number"
                        label="Numero de factura"
                        type="number"
                        
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        
                      <v-select
                        :items="types_work"
                        item-value="id"
                        item-text="name"

                        label="Tipos de ordenes "
                        v-model="from.types_work_id"
                       ></v-select>
                    </v-col>

                    <v-col cols="12">
                        
                      <v-select
                        :items="status"
                        item-value="id"
                        item-text="name"

                        label="Tipos de estados "
                        v-model="from.status_id"
                       ></v-select>
                    </v-col>
                     <v-col cols="12">
                      <v-text-field
                        label=" Fecha de creación"
                        disabled
                        filled
                        v-model="currentDate"
                      ></v-text-field>
                    </v-col>
  
                  </v-row>
                </v-container>
              </v-card-text>
            <v-card-actions>
               <v-spacer></v-spacer>

              <v-btn
                color="primary darken-1"
                text
                @click="dialog3 = false"
              >
                Cerrar
              </v-btn>
               <v-btn 
               color="blue darken-1" 
               text 
               @click="save"
               > 
               Guardar
                </v-btn>

            </v-card-actions>
          </v-card>
        </v-dialog>
  </div>
</template>

<script>

export default {
    props:{
      types_work : Array,
      status:Array
    },
    
   data(){
       return{
         dialog3: false,
         from:{
             order_number:'',
             types_work_id:'',
             status_id:''
         }
       }
   },
   methods:{
     
       save(){
         axios.post("api/order/save/work",this.from)
          .then((response) => {
             this.dialog3 =false
            this.from={
             order_number:'',
             types_work_id:'',
             status_id:''
             }
            this.$emit('GetSupplement');
            

        })
        .catch((errors) => {});
       },
       
   },
   computed:{
     currentDate() {
      return moment().format('D-M-Y')
    },
   }
}
</script>

<style>

</style>