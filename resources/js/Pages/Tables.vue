<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalAddTable from '@/Pages/Tables/ModalAddTable.vue';
import ModalUpdateTable from '@/Pages/Tables/ModalUpdateTable.vue';
import { ref, onMounted, onUpdated } from 'vue';
import { useStore } from 'vuex';
const showAddTable = ref(false);
const showUpdateTable = ref(false);
const tabSelect = ref(null);
const lastTable = ref(null);
const tableSelect = ref(null)
export default{
    data()
    {
        const store = useStore();
        onMounted(()=>{
            getTables();
        });

        onUpdated(()=>{
            initZones();
        });

        const addTable = (i) =>{
            tabSelect.value =  i;
            showAddTableModal();
        }

        const updateTable = (table,i) =>{
            tableSelect.value =  table;
            tabSelect.value =  i;
            showUpdateTableModal();
        }

        const showUpdateTableModal = () =>{
            showUpdateTable.value = !showUpdateTable.value;
        }


        const showAddTableModal = () =>{
            showAddTable.value = !showAddTable.value;
        }

        const closeTableModal = () =>{
            showAddTable.value = !showAddTable.value;
        }

        const closeAddTableModal = (room) =>{
            showAddTable.value = !showAddTable.value;
            getTables(room);
        }

        const closeUpdateTableModal = (room) =>{
            showUpdateTable.value = !showUpdateTable.value;
            getTables(room);
        }


        const getTables = async(room = null) =>
        {
            try{
                await axios.post('/get-tables')
                .then(response=>{
                    store.state.tables = response.data;
                    const tablesNumber = [];
                    for(let i = 0 ; i < Object.keys(store.state.tables).length; i++)
                    {
                        for(let j = 0; j < store.state.tables[Object.keys(store.state.tables)[i]]['tables'].length; j++)
                        {
                            let tableFor = store.state.tables[Object.keys(store.state.tables)[i]]['tables'];
                            tablesNumber.push(tableFor[j].number)
                        }
                    }


                    let ordenado = tablesNumber.sort(function(a, b){return a - b});
                    lastTable.value = Number(ordenado.splice(tablesNumber.length-1,1)) + 1;

                setTimeout(() => {
                    initZones();
                    zone((room!=null)?room:Object.keys(store.state.tables)[0]);
                }, 1);

                });

            }
            catch(error)
            {
                console.log(error);
            }
        }

        const zone =(zoneClick)=>
        {
            for(let i =0; i < document.getElementsByClassName("zones").length; i++)
            {
                document.getElementsByClassName("zones")[i].classList.remove("display");
            }

            for(let i =0; i < document.getElementsByClassName('space').length; i++)
            {
                document.getElementsByClassName('space')[i].classList.remove('active');
            }
            document.getElementsByClassName(zoneClick)[0].classList.add('active');
            document.getElementById(zoneClick).classList.add("display");
        }

        const initZones = () =>{

            let zoneInitial= Object.keys(store.state.tables)[0];
            document.getElementsByClassName(zoneInitial)[0].classList.add('active');
            document.getElementById(zoneInitial).classList.add("display");
        }
        return {
            zone,addTable,showAddTable,showAddTableModal,showUpdateTable,updateTable,showUpdateTableModal,tabSelect,lastTable,closeAddTableModal,closeTableModal,closeUpdateTableModal,tableSelect
        }
    },
    components:{
        AppLayout,
        ModalAddTable,
        ModalUpdateTable
    }
}

</script>
<style>
.zones.display{

  display: block !important;

}
.space.active{
    background-color: #BDC3C7;
    color: #2C3E50;
    font-weight: bold;
}

.tables>div{
    width: 100%;
    height: 100%;
    padding: 19px 0px 0px 0px;
    cursor:pointer;
}

@media (max-width: 1023px) {
    .tables>div
    {
        padding: 4px 0px 0px 0px;

        font-size:18px;
    }
}
</style>
<template>
    <AppLayout title="Dashboard">
       <!---
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>
        -->

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl">
                    <ul class="relative pt-3 mb-3 lg:mb-0 pl-3 bg-gray-800 rooms">
                        <li  @click="zone(i)" :class="i" class="float-left p-3 py-3 mr-2 text-white bg-gray-600 cursor-pointer rounded-t-sm space hover:bg-gray-500"  v-for="(table, i ) in $store.state.tables" :key="i">{{ i }}</li>
                        <div class="clear-both"></div>
                    </ul>
                    <div v-for="(data_table, i ) in $store.state.tables" :key="i" :id="i" :class="i" class="hidden zones" >
                        <a  v-for="(table, j ) in data_table['tables']" :key="j" :id="'table_'+table.id"  class="float-left w-12 h-12 mb-2 ml-2 mr-2 text-3xl text-center text-white bg-gray-500 rounded-md lg:mt-4 lg:w-20 lg:h-20 hover:bg-gray-400 tables">
                            <div @click="updateTable(table,i)" class="border-b-4 border-gray-400 border-solid rounded-b-md"  >{{table.number}}</div>
                        </a>
                        <a @click="addTable(i)" class="float-left w-12 h-12 mb-2 ml-2 mr-2 text-3xl text-center text-white bg-gray-500 rounded-md lg:mt-4 lg:w-20 lg:h-20 hover:bg-gray-400 tables">
                            <div class="border-b-4 border-gray-400 border-solid rounded-b-md"  >+</div>
                        </a>
                    </div>
                    <modal-add-table :show = showAddTable @show="closeAddTableModal" @close="closeTableModal" :room = "tabSelect" :lastTable="lastTable"></modal-add-table>
                    <modal-update-table :show = showUpdateTable @show="closeUpdateTableModal" @close="closeUpdateTableModal" :room="tabSelect" :table="tableSelect"></modal-update-table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
