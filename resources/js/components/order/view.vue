<template>
  <div>
      <Menu/>
      <v-app id="inspire">
    <div>
            <v-container fluid >

        <v-toolbar flat >
          <v-toolbar-title>Ordenes</v-toolbar-title>

          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <CreateOrderWork :status="status" :types_work="types_work" @GetSupplement="getsupplement()" />
          <CreateTypesWork  @GetSupplement="getsupplement()" />
          <OrderAssignment :status="status" :order_work="order_work" :operator="operator" @GetOrder="getorder()" />
        </v-toolbar>
            </v-container>
       <v-container fluid>
        <v-data-table
          :headers="headers"
          :items="data"
          hide-default-footer
          class="elevation-1"
        >
        <template  v-slot:item.orderwork.order_number="{ item }">
            BS-{{item.orderwork.order_number}}
          </template>

          <template v-slot:item.orderwork.created_at="{ item }">
            {{formatDate(item.orderwork.created_at)}}
          </template>

          <template v-slot:item.date_assignment="{ item }">
            {{formatDate(item.date_assignment) }}
          </template>

            <template v-slot:item.date_execution="{ item }">
            {{formatDate(item.date_execution) }}
          </template>

           <template v-slot:item.value_work="{ item }">
             {{ valueFormat(item.value_work)  }}
          </template>

          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" @click="editItem(item)">
              mdi-pencil
            </v-icon>
          </template>
        </v-data-table>
      </v-container>
      
       <v-pagination
        v-model="pagination.current"
        :length="pagination.total"
        @input="onPageChange"
      >
      </v-pagination>
    </div>
              <v-dialog v-model="dialog5" max-width="600px">
 <v-card>
            <v-card-title>
              Editar Orden  
            </v-card-title>
           <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="6">
                      <v-text-field
                        v-model="from.order_number"
                        label="Numero de factura"
                        disabled
                      ></v-text-field>
                    </v-col>

                    <v-col cols="6">
                      <v-text-field
                        v-model="from.ordertype_name"
                        label="Tipo de orden"
                        disabled
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6">
                      <v-text-field
                        v-model="from.operator_name"
                        label="Operario"
                        disabled
                      ></v-text-field>
                    </v-col>

                    <v-col cols="6">
                      <v-text-field
                        label=" Fecha de creacion"
                        disabled
                        
                        v-model="from.created_at"
                      ></v-text-field>
                    </v-col>

                   <v-col cols="6">
                      <v-text-field
                        label=" Fecha de asignacion"
                        disabled
                        
                        v-model="from.date_assignment"
                      ></v-text-field>
                    </v-col>

                     

                    <v-col cols="6">
                      <v-text-field
                        label="Valor del trabajo"
                        type="number"
                        filled
                        v-model="from.value_work"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="4">
                        
                      <v-select
                        :items="status"
                        item-value="id"
                        item-text="name"

                        label="Tipos de estados "
                        v-model="from.status_id"
                       ></v-select>
                    </v-col>
                                      <v-col
      cols="8"
     
    >
      <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="from.date_execution"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
          v-model="from.date_execution"
            label="Fecha  de asignación"
            prepend-icon="mdi-calendar"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="from.date_execution"
          no-title
          scrollable
          :min="from.created_ats"
          :max="from.date_assignments"
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="menu = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="$refs.menu.save(from.date_execution)"
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
            <v-card-actions>
               <v-spacer></v-spacer>

              <v-btn
                color="primary darken-1"
                text
                @click="close"
              >
                Cerrar
              </v-btn>
               <v-btn 
               color="blue darken-1" 
               text 
               @click="update"
               > 
               Guardar
                </v-btn>

            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-app>
  </div>

</template>

<script>
import Menu  from '../menu'
import CreateOrderWork from './createOrderWork.vue'
import CreateTypesWork from './createTypesWork.vue'
import OrderAssignment from './orderAssignment.vue'

export default {
    components:{
        Menu,
        CreateOrderWork,
        CreateTypesWork,
        OrderAssignment,
    },
   
    data () {
      return {
        headers: [
        { text: "Id", value: "id" },
        { text: "N° Orden", value:  "orderwork.order_number" },
        { text: "Fecha Creación", value: "orderwork.created_at" },
        { text: "Tipo de trabajo ", value: "orderwork.ordertype.name" },
        { text: "Estado", value: "orderwork.status.name" },
        { text: "Identificación Operador ", value: "operator.cc" },
        { text: "Nombre Operador ", value: "operator.name" },
        { text: "Fecha asignación ", value: "date_assignment" },
        { text: "Fecha ejecución ", value: "date_execution" },
        { text: "Valor ", value: "value_work" },
        {
          text: "Actions",
          value: "actions",
          sortable: false,
          align: "center",
        },
      ],
        data:[],
        status:[],
        order_work:[],
        operator:[],
        types_work:[],
        data: [],
        pagination: {
            current: 1,
            total: 0,
          },
        dialog5:false,
        from:{
          order_number:'',
          created_at:'',
          created_ats:'',
          operator_name:'',
          ordertype:'',
          date_execution:'',
          date_assignment:'',
          date_assignments:'',
           status_id:'',
          value_work:'',
          id:'',
        },
        menu: false,
          modal: false,
          menu2: false,
          date:''
       }
    },
   
    mounted(){
      this.getsupplement();
      this.getorder()
    }, 
    methods:{
      getsupplement(){
         axios.get("api/order/get/supplement").then((response) => {
             this.status= response.data.status
             this.types_work =response.data.types_work
             this.order_work = response.data.order_work
             this.operator =response.data.operator
        })
        .catch((errors) => {});
       },
       getorder(){
                
         axios.post("api/order/get?page=" + this.pagination.current) .then((response) => {
             this.data= response.data.data
             this.pagination.current = response.data.current_page;
             this.pagination.total = response.data.last_page;
        })
        .catch((errors) => {});
       },
       onPageChange() {
         this.getorder();
       },
       formatDate(value){
           return (value)? moment(value).format('DD/MM/YYYY') : '';
        },
        
      valueFormat( item){
        return (item)? '$'+ item: '';
      },
      editItem(item){
        const value ={
          'id': item.id,
          'order_number': 'BS-'+item.orderwork.order_number,
          'operator_name': item.operator.name,
          'ordertype_name': item.orderwork.ordertype.name,
          'created_at': moment(item.orderwork.created_at).format('DD/MM/YYYY'),
          'created_ats': moment(item.orderwork.created_at).format('YYYY-MM-DD'),
          'date_assignment': moment(item.date_assignment).format('DD/MM/YYYY'),
          'date_assignments': moment(item.date_assignment).format('YYYY-MM-DD'),
          'status_id':item.orderwork.status.id,
          'date_execution': (item.date_execution)?  moment(item.date_execution).format('YYYY-MM-DD'):''
                           ,
          'value_work':item.value_work
        }
        this.from=value
        
          this.dialog5 =true
      },
      close(){
        this.from={
          order_number:'',
          created_at:'',
          created_ats:'',
          operator_name:'',
          ordertype:'',
          date_execution:'',
          date_assignment:'',
          date_assignments:'',
          status_id:'',
          value_work:'',
          id:'',
        }
        this.dialog5=false
      },
      update(){
        
        const data={
          'id':this.from.id,
          'date_execution':this.from.date_execution,
          'value_work':this.from.value_work,
          'order_work_id':this.from.order_number,
          'status_id':this.from.status_id
        }
           axios.post("api/order/update",data).then((response) => {
             this.getorder()  
             this.close()           
        })
        .catch((errors) => {}); 
      }
      },
      computed:{
        
      }
      

}
</script>

