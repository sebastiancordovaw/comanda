<template>
    <div v-if="$store.state.products.length>0">

        <button @click = "changeTable" class="float-right p-1 text-orange-800 bg-orange-200 border-t border-b border-l border-r border-orange-700 hover:bg-orange-300 text-sm">Cambiar Mesa</button>
        <select v-model="selectMesa" class="float-right text-sm p-1 text-orange-800" >
            <option value=null >Cabiar</option>
            <template v-for="(data_table, i ) in $store.state.tables" :key="i"  >
                <template v-for="(table, i ) in data_table['tables']" :key="i"  >
                    <option v-if="table.status==0" :value="table.id">{{table.number}}</option>
                </template>
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
            store.state.loadData = true;
            store.dispatch("changeTableAction",{'newTable':selectMesa.value,'oldTable':store.state.tableActivate});
            
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