<template>
  <div>
    <v-btn color="primary" class="ma-2" small dark @click="dialog4 = true">
      <v-icon dark> mdi-plus </v-icon>

      Asignar
    </v-btn>
    <v-dialog v-model="dialog4" max-width="500px">
      <v-card>
        <v-card-title> Asignar Ordenes de trabajo </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-select
                  :items="order_work"
                  item-value="id"
                  item-text="code_order"
                  label=" ordenes de trabajo "
                  v-model="from.order_work_id"
                  @change="getdate()"
                ></v-select>
              </v-col>
              <v-col cols="12">
                <v-select
                  :items="operator"
                  item-value="id"
                  item-text="name"
                  label="Operadores"
                  v-model="from.operator_id"
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
                <v-menu
                  ref="menu"
                  v-model="menu"
                  :close-on-content-click="false"
                  :return-value.sync="date"
                  transition="scale-transition"
                  offset-y
                  min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="date"
                      label="Fecha  de asignación"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="date"
                    no-title
                    scrollable
                    :min="dateMin"
                  >
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="menu = false">
                      Cancel
                    </v-btn>
                    <v-btn text color="primary" @click="$refs.menu.save(date)">
                      OK
                    </v-btn>
                  </v-date-picker>
                </v-menu>
              </v-col>
              <v-spacer></v-spacer>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="primary darken-1" text @click="dialog4 = false">
            Cerrar
          </v-btn>
          <v-btn color="blue darken-1" text @click="save"> Guardar </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    status: Array,
    order_work: Array,
    operator: Array,
  },
  data() {
    return {
      dialog4: false,
      menu: false,
      modal: false,
      menu2: false,
      date: moment(new Date()).format("YYYY-MM-DD"),
      date_mins: '',
      from: {
        order_work_id: '',
        status_id: '',
        operator_id: '',
      },
    };
  },
  methods: {
    getdate() {
      const order_work_id = {
        id: this.from.order_work_id,
      };
      axios.post("api/order/get/date/order_work", order_work_id)
        .then((response) => {
          this.date_mins = moment(response.data.date[0].created_at).format("YYYY-MM-DD");
        })
        .catch((errors) => {});
    },

    save() {
      const data = {
        order_work_id: this.from.order_work_id,
        status_id: this.from.status_id,
        users_id: this.from.operator_id,
        date_assignment: this.date,
      };

      if (data.order_work_id =='') {
         toast.fire({
                 icon: 'error',title: 'el campo ordenes de trabajo es obligatorio'
           })
       
      }else if(data.status_id == ''){
         toast.fire({
                 icon: 'error',title: 'el campo estado es obligatorio'
           })

      }else if(data.users_id ==''){
         toast.fire({
                 icon: 'error',title: 'el campo operador es obligatorio'
           })
      }else if(data.date_assignment ==''){
           toast.fire({
                 icon: 'error',title: 'el campo operador es obligatorio'
           })
      } else {
         axios.post("api/order/save/work/assignment", data)
            .then((response) => {
             this.dialog4 = false;
             this.date_mins = "";
             this.from = {
                 order_work_id: "",
                 status_id: "",
                 operator_id: "",
          };
          toast.fire({
               icon: 'success',title: 'la información se ha guardado exitosamente'
             })
           this.$emit("GetOrder");

        })
        .catch((errors) => {});
      }
     
    },
  },
  computed: {
    dateMin() {
      return this.date_mins == "" ? "" : this.date_mins;
    },
  },
};
</script>

<style>
</style>