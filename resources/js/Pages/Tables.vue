<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalAddTable from '@/Pages/Tables/ModalAddTable.vue';
import ModalUpdateTable from '@/Pages/Tables/ModalUpdateTable.vue';

import ModalAddRoom from '@/Pages/Rooms/ModalAddRoom.vue';
import ModalUpdateRoom from '@/Pages/Rooms/ModalUpdateRoom.vue';

import Swal from 'sweetalert2';

import { ref, onMounted, onUpdated } from 'vue';
import { useStore } from 'vuex';

const showAddTable = ref(false);
const showUpdateTable = ref(false);

const showAddRoom = ref(false);
const showUpdateRoom = ref(false);

const tabSelect = ref(null);
const lastTable = ref(null);
const tableSelect = ref(null)
export default{
    data()
    {

        
        const store = useStore();
        onMounted(()=>{
            getTables();

            let heigth = window.screen.height;
            setTimeout(() => {
                for(let i = 0; i<document.getElementsByClassName("bodyApp").length; i++)
                {
                    document.getElementsByClassName("bodyApp")[i].style.minHeight = (heigth - 175)+"px";
                }
            }, 1);
        });

        onUpdated(()=>{
            initZones();
        });

        const addTable = (i) =>{
            tabSelect.value =  i;
            showAddTableModal();
        }

        const addRoom = () =>{
            showAddRoom.value = !showAddRoom.value
        }

        const updateRoom = () =>{
            showUpdateRoom.value = !showUpdateRoom.value
        }

        const closeAddRoom = (room) =>{
            showAddRoom.value = !showAddRoom.value;
            room = (room)?room:tabSelect.value;
            getTables(room);
        }

        const closeUpdateRoom = (room) =>{
            showUpdateRoom.value = !showUpdateRoom.value;
            room = (room)?room:tabSelect.value;
            getTables(room);
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

        const delRoom = async() =>{

            let formData = new URLSearchParams();
            formData.append("name", tabSelect.value);
            const AxiosConfig = {
                method: 'POST',
                url: '/delRoom',
                data: formData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            };

            await axios(AxiosConfig)
            .then(response=>
            {
                getTables(Object.keys(store.state.tables)[0]);
            })
            .catch(error=>{
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No puede eliminar una sala con mesas',
                confirmButtonColor: '#111827',
                });
            });
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

        const capitalize = (s) =>
        {
            return s[0].toUpperCase() + s.slice(1);
        }

        const zone =(zoneClick)=>
        {
            zoneClick = capitalize(zoneClick);
            for(let i =0; i < document.getElementsByClassName("zones").length; i++)
            {
                document.getElementsByClassName("zones")[i].classList.remove("display");
            }

            for(let i =0; i < document.getElementsByClassName('space').length; i++)
            {
                document.getElementsByClassName('space')[i].classList.remove('active');
            }

            if(zoneClick)
            {
                document.getElementsByClassName(zoneClick)[0].classList.add('active');
                document.getElementById(zoneClick).classList.add("display");
                tabSelect.value = zoneClick;
            }


        }

        const initZones = () =>{

            let zoneInitial= Object.keys(store.state.tables)[0];
            if(zoneInitial)
            {
                if(document.getElementsByClassName(zoneInitial).length)
                {
                    document.getElementsByClassName(zoneInitial)[0].classList.add('active');
                }
                console.log(document.getElementById(zoneInitial).length);
                if(document.getElementById(zoneInitial).length)
                {
                    document.getElementById(zoneInitial).classList.add("display");
                }
            }
        }
        return {
            addRoom,
            delRoom,
            showAddRoom,
            updateRoom,
            closeAddRoom,
            closeUpdateRoom,
            showUpdateRoom,
            zone,
            addTable,
            showAddTable,
            showAddTableModal,
            showUpdateTable,
            updateTable,
            showUpdateTableModal,
            tabSelect,lastTable,
            closeAddTableModal,
            closeTableModal,
            closeUpdateTableModal,
            tableSelect
        }
    },
    components:{
        AppLayout,
        ModalAddTable,
        ModalUpdateTable,
        ModalAddRoom,
        ModalUpdateRoom
    }
}

        ModalUpdateRoom
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
            <h2 class="text-xl font-semibold leading-tight text-whte">
                Dashboard
            </h2>
        </template>
        -->

        <div class="py-12 bodyApp">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl">
                    <ul class="relative pt-3 pl-3 mb-3 bg-gray-800 lg:mb-0 rooms">
                        <li  @click="zone(i)" :class="i" class="float-left p-3 py-3 mr-2 text-white bg-gray-600 rounded-t-sm cursor-pointer space hover:bg-gray-500"  v-for="(table, i ) in $store.state.tables" :key="i">{{ i }}</li>

                        <button @click="delRoom" class="float-right p-2 mr-4 text-white bg-red-600 rounded-sm hover:bg-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <button @click="updateRoom" class="float-right p-2 mr-4 text-whte bg-yellow-400 rounded-sm hover:bg-yellow-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>

                        <button @click="addRoom" class="float-right p-2 mr-4 text-white bg-green-400 rounded-sm hover:text-white hover:bg-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                        <div class="clear-both"></div>
                    </ul>
                    <div v-for="(data_table, i ) in $store.state.tables" :key="i" :id="i" :class="i" class="hidden zones" >
                        <a  v-for="(table, j ) in data_table['tables']" :key="j" :id="'table_'+table.id"  class="float-left w-12 h-12 mb-2 ml-2 mr-2 text-3xl text-center text-white bg-gray-500 rounded-md lg:mt-4 lg:w-20 lg:h-20 hover:bg-gray-400 tables">
                            <div @click="updateTable(table,i)" class="border-b-4 border-gray-400 border-solid rounded-b-md"  >{{table.number}}</div>
                        </a>
                        <a @click="addTable(i)" class="float-left w-12 h-12 mb-2 ml-2 mr-2 text-3xl text-center text-white bg-gray-500 rounded-md lg:mt-4 lg:w-20 lg:h-20 hover:bg-gray-400 tables">
                            <div class="text-4xl border-b-4 border-gray-400 border-solid rounded-b-md" >+</div>
                        </a>
                    </div>
                    <modal-add-table :show = showAddTable @show="closeAddTableModal" @close="closeTableModal" :room = "tabSelect" :lastTable="lastTable"></modal-add-table>
                    <modal-update-table :show = showUpdateTable @show="closeUpdateTableModal" @close="closeUpdateTableModal" :room="tabSelect" :table="tableSelect"></modal-update-table>
                    <modal-add-room :show="showAddRoom" @close="closeAddRoom" @show="closeAddRoom"></modal-add-room>
                    <modal-update-room :show="showUpdateRoom" :roomselect="tabSelect" @close="closeUpdateRoom"  @show="closeUpdateRoom"></modal-update-room>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
