<template>
    <div v-if="$store.state.products.length>0">

        <button @click = "changeTable" class="rounded-r-sm float-right p-1 text-white bg-green-400 border-0 hover:bg-green-500 text-sm">Cambiar</button>
        <select v-model="selectMesa" class="rounded-l-sm float-right text-sm p-1 text-orange-800 border-0" >
            <option value=null >Cambiar</option>
            <template v-for="(table, i ) in tables" :key="i"  >
                <option v-if="table.status==0" :value="table.id">{{table.number}}</option>
            </template>
        </select>
        <div class="clear-both"></div>

    </div>

</template>

<script>
import {ref, onMounted} from 'vue';
import { useStore } from 'vuex';
const tables = ref([]);
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
            orderTables();
        }
      }

        const orderTables = async() =>
        {
            const tablesNumber = [];
            await axios.post('/get-tables')
                .then(response=>{

                    store.state.tables = response.data;
                    for(let i = 0 ; i < Object.keys(store.state.tables).length; i++){
                        for(let j = 0; j < store.state.tables[Object.keys(store.state.tables)[i]]['tables'].length; j++)
                        {
                            let tableFor = store.state.tables[Object.keys(store.state.tables)[i]]['tables'];
                            tablesNumber.push({'id':tableFor[j].id,'number':tableFor[j].number,'status':tableFor[j].status})
                        }
                    }
                });

            tables.value =  tablesNumber.sort((x, y) => x.number - y.number);
            console.log(tables.value);
        }

        onMounted(()=>{
            orderTables();
        });


      return {changeTable,selectMesa,tables,orderTables}
    },
    components:{
    }
}
</script>
