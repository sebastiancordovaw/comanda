<template>
    <div v-if="$store.state.products.length>0">

        <button @click = "changeTable" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 p-2  border-t border-b border-l border-r float-right border-orange-700 text-orange-800 ">Cambiar Mesa</button>
        <select v-model="selectMesa" class="float-right" >
            <option value=null >Cabiar Mesa</option>
            <template v-for="(table, i ) in $store.state.tables" :key="i"  >
                <option v-if="table.status==0" :value="table.id">{{table.number}}</option>
            </template>
            
        </select>
        <div class="clear-both"></div>

    </div>
    
</template>

<script>
import {ref} from 'vue';
import { useStore } from 'vuex';
const selectMesa = ref(null);

export default {
    data(){
        return{

        }
    },
    setup(){
      const store = useStore();
      const changeTable = () =>{
        if(selectMesa.value!="" && selectMesa.value!=null && selectMesa.value !=store.state.tableActivate)
        {
            store.dispatch("changeTableAction",{'newTable':selectMesa.value,'oldTable':store.state.tableActivate});

            ////////////////////
            
            for(let i = 0; i<document.getElementsByClassName("tables").length; i++){
                document.getElementsByClassName("tables")[i].classList.remove("selectTable")
            }
            
            store.state.tableActivate       = selectMesa.value;
            localStorage.setItem("table",selectMesa.value)
            document.getElementById("table_"+selectMesa.value).classList.add("selectTable");

            selectMesa.value = null;
        
        }
        
      }
      return {changeTable,selectMesa}
    },
    components:{
    }
}
</script>