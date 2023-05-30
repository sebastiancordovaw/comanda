<template>
    <modal  :show = "show" ><!--@close="ChangeStateModal">-->
            <div class="grid grid-cols-1 p-6 mt-10 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="num" class="block text-sm font-medium leading-6 text-gray-900">Número mesa</label>
                    <div class="mt-2">
                    <input type="text" v-model="num" id="num"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="salon" class="block text-sm font-medium leading-6 text-gray-900">Salón</label>
                    <div class="mt-2">
                    <select v-model="salon" id="salon" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6">
                        <option v-for="(table,i) in $store.state.tables" :key="i" :value="i">{{i}}</option>
                    </select>
                    </div>
                </div>
            </div>
            <ul v-if="errores!=''"  class="p-4 rounded-sm bg-red-400 text-white ">
                 <li v-for="(error,key) in errores" :key ="key">
                    {{ error[0] }}
                 </li>
            </ul>
            <div class="p-4 ">
                <button @click = "addTable" class="float-right p-2 text-white bg-gray-800 rounded-sm hover:bg-gray-900">Confirmar</button>
                <button @click = "ChangeStateModalCloseButton" class="float-right p-2 mr-4 text-white bg-cyan-300 rounded-sm hover:bg-cyan-400">Cerrar</button>
                <div class="clear-both"></div>
            </div>


    </modal>
</template>

<script>
import Modal from '@/Components/Modal.vue'

import { ref, onUpdated, onMounted } from 'vue';
import { useStore } from 'vuex';

let closemodal= null;
const salon = ref(null);
const num = ref('');
const errores = ref('');
export default {
    emits:['show'],
    setup(props,{emit})
    {
        const store = useStore();

        const ChangeStateModal = () =>{

        }


        closemodal = ()=>{
            emit('close',props.room);
            errores.value = '';
        }

        onUpdated(() => {
            salon.value = props.room;
            num.value = props.lastTable;
            errores.value = '';
        })

        const addTable = async() => {
            let formData = new URLSearchParams();
            formData.append("number", num.value);
            formData.append("salon", salon.value);
            const AxiosConfig = {
                method: 'POST',
                url: '/insertTable',
                data: formData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            };

            await axios(AxiosConfig)
            .then(response=>
            {
                emit('show',salon.value);
                errores.value = '';
            })
            .catch(error=>{
                if(error.response.status === 422)
                {

                    errores.value = error.response.data.errors;
                }
            })

        }


        return {addTable,salon,num,ChangeStateModal,errores}

    },
    data()
    {
        return{

        }
    }
    ,components:{
        Modal
    },
    methods:{
        ChangeStateModalCloseButton()
        {
            closemodal();
        }
    },props:['show','room','lastTable']
}

</script>

<style>
.pay{
    background-color:#7bed9f;
}
</style>
