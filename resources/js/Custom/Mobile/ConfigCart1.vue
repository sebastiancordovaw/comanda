<template>
<modal  :show = "show" @close="ChangeStateModal">
     <div class="p-4">
        <p class="mb-3 text-lg">{{product.name}}</p>
        <div class="grid grid-cols-12 text-5xl">
            <div class="col-span-4 text-center cursor-pointer" @click="decrement">
                -
            </div>
            <div class="col-span-4 text-center">
                <span>{{ count }}</span>
            </div>

            <div class="col-span-4 text-center cursor-pointer" @click="increment">
                +
             </div>

            <div class="col-span-12 ">
                <input v-model="comment" placeholder="Agregar Nota..." type="text" name="" id="" class="w-full border-t-0 border-b border-l-0 border-r-0 border-gray-400 focus:ring-transparent focus:border-gray-800"/>
            </div>

            <div class="col-span-12 ">
                <label class="text-lg">Editar Total</label>
                <input v-model="total" placeholder="Editar Total.." type="number" name="" id="" class="w-full border-t-0 border-b border-l-0 border-r-0 border-gray-800 focus:ring-transparent focus:border-gray-800"/>
            </div>

        </div>
        <div class="mt-2">
                <button @click = "cancel"  class="p-2 text-white bg-gray-400 rounded-sm hover:bg-gray-500 float-left ">Cancelar</button>
                <button @click = "confirm" class="p-2 text-white bg-green-400 rounded-sm hover:bg-green-500  float-right">Confirmar</button>
                <div class="clear-both"></div>
        </div>
     </div>

</modal>
</template>

<script>
import Modal from '@/Components/Modal.vue';
import {ref, onUpdated, onMounted } from 'vue';
import { useStore } from 'vuex';
const count = ref(0);
const comment = ref('');
const total = ref(0);
export default { 
    emits:['show'],
    setup(props,{emit})
    {
        const store = useStore();
        onMounted(()=>{
 
        })

        onUpdated(()=>{
            
            if(store.state.cart1[store.state.tableActivate] && store.state.cart1[store.state.tableActivate][props.product.id])
            {
                count.value = store.state.cart1[store.state.tableActivate][props.product.id].count;
                comment.value = store.state.cart1[store.state.tableActivate][props.product.id].note;
                total.value = store.state.cart1[store.state.tableActivate][props.product.id].total_amount;
            }
            else
            {
                count.value = (props.product.count>0)?props.product.count:1;
                comment.value = props.product.note;
                total.value = (props.product.total_amount>=0)?props.product.total_amount:props.product.price;
            } 
        })

        const ChangeStateModal = () =>{

        }

        const increment = () =>{

            count.value++;
            total.value = props.product.price * count.value;
        }

        const decrement = () =>{
            if(count.value>=2)
            {
                count.value--;
                total.value =  props.product.price  * count.value;
            }

        }

        const cancel = () =>{
            emit("CloseConfigCart1");
        }

        const confirm = () =>{
            emit("confirmConfigCart1",{"count":count.value,"comment":comment.value, "total_amount":total.value});
        }

        return {
            ChangeStateModal,count,increment,decrement,cancel,comment,confirm,total
        }
    },
    components:{
        Modal
    },
    props:['show','product']
}
</script>
