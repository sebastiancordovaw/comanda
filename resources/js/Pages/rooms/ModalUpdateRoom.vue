<template>
    <modal  :show = "show" ><!--@close="ChangeStateModal">-->
            <div class="grid grid-cols-1 p-6 mt-10 gap-x-6 gap-y-8 sm:grid-cols-12">
                <div class="sm:col-span-12">
                    <label for="room" class="block text-sm font-medium leading-6 text-gray-900">Sala</label>
                    <div class="mt-2">
                    <input type="text" v-model="room" id="room"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6">
                    </div>
                </div>

            </div>
            <ul v-if="errores!=''"  class="p-4 text-white bg-red-400 rounded-sm ">
                 <li v-for="(error,key) in errores" :key ="key">
                    {{ error[0] }}
                 </li>
            </ul>
            <div class="p-4 ">
                <button @click = "updateRoom" class="float-right p-2 text-white bg-green-400 rounded-sm hover:bg-green-500">Confirmar</button>
                <button @click = "ChangeStateModalCloseButton" class="float-right p-2 mr-4 text-gray-800 bg-gray-300 rounded-sm hover:text-white hover:bg-gray-400">Cerrar</button>
                <div class="clear-both"></div>
            </div>

    </modal>
</template>


<script>
import Modal from '@/Components/Modal.vue'

import { ref, onUpdated, onMounted } from 'vue';
import { useStore } from 'vuex';

let closemodal= null;
const room = ref('');
const errores = ref('');
export default {
    emits:['show'],
    setup(props,{emit})
    {
        const store = useStore();

        const ChangeStateModal = () =>{
        }

        closemodal = ()=>{
            emit('close',props.roomselect);
            errores.value = '';
        }
        onUpdated(() => {
            errores.value = '';
            room.value = props.roomselect;
        })

        const updateRoom = async() => {
            let formData = new URLSearchParams();
            formData.append("name", room.value);
            formData.append("old_name", props.roomselect);
            const AxiosConfig = {
                method: 'PUT',
                url: '/updateRoom',
                data: formData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            };

            await axios(AxiosConfig)
            .then(response=>
            {
                emit('show',room.value);
                errores.value = '';
            })
            .catch(error=>{
                if(error.response.status === 422)
                {
                    errores.value = error.response.data.errors;
                }
            })
        }
        return {updateRoom,room,ChangeStateModal,errores}
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
    },props:['show', 'roomselect']
}

</script>

<style>
.pay{
    background-color:#2ecc71;
}
</style>
