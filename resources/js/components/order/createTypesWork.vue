<template>
  <div>
    <v-btn color="primary" class="ma-2" small @click="dialog2 = true">
      <v-icon dark> mdi-plus </v-icon>

      Tipos de trabajo
    </v-btn>
    <v-dialog v-model="dialog2" max-width="500px">
      <v-card>
        <v-card-title> Crear Tipo de trabajo </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="form.name" label="Nombre"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  type="number"
                  v-model="form.code"
                  label="Código"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="primary darken-1" text @click="dialog2 = false">
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
  data() {
    return {
      dialog2: false,

      form: {
        code: "",
        name: "",
      },
    };
  },
  methods: {
    save() {
         const pattern = new RegExp('^[A-Z]+$', 'i');

      if (this.form.name == "" || !pattern.test(this.form.name) || /[^A-Za-z\d]/.test(this.form.name)) {
         toast.fire({
                 icon: 'error',title: 'el campo Nombre es obligatorio y debe ser valido'
           })
      }else if(!Number.parseInt(this.form.code)){
        toast.fire({
                 icon: 'error',title: 'el campo Código es obligatorio y debe ser numerico'
           })
      } else {
        axios.post("api/order/save/types/work", this.form)
            .then((response) => {
          this.dialog2 = false;
            this.form= {
               code: "",
               name: "",
            }
             toast.fire({
               icon: 'success',title: 'la información se ha guardado exitosamente'
             })
          this.$emit("GetSupplement");
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
  },
};
</script>

<style>
</style>
</style>