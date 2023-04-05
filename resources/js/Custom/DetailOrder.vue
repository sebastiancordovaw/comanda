<template>
    <div v-show="$store.state.products.length">
        <div class="grid grid-cols-12 bg-gray-400 hover:bg-orange-300 border-orange-700 mt-1 border-l-4" v-for="(product) in $store.state.products" :key  = "product.id" :id="product.id" :class ="product.status?'':'delete'">
        <div class="col-span-2 p-2">{{ product.count }}</div>
        <div class="col-span-5 p-2"><b>{{ product.name }}</b></div>
        <div class="col-span-3 text-center p-2">${{ product.amount }}</div>
        <div class="col-span-2 p-2">
            <svg v-if="product.status>0" :id="'trash_'+product.id"  @click="delProduct(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-orange-400 hover:text-orange-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg></div>
        </div>
        <div class="text-right bg-gray-600 px-6 py-3 text-white"><b>Total: $ {{ total }}</b></div>
    </div>
</template>

<script>
import { useStore } from 'vuex';
export default {
    
    data(){
        return{

        }
    },
    setup() {
        const store = useStore();

        const delProduct = product => {
            store.dispatch('deleteProductOrder', product);
        }
        return {delProduct}
    },
    computed:{
        total()
        {
            const store = useStore();
            let total = 0;
            for(let i= 0; i< store.state.products.length; i++)
            {
                if(store.state.products[i].status>0)
                {
                    total += store.state.products[i].amount;
                }
                
            }
            return store.state.total_amount_order = total;
        }
    }
}
</script>

<style>
.delete{
    color:grey;
    border-color: dimgrey;
    text-decoration:line-through;
}

.delete:hover{
    background-color:#bdc3c7 !important;
}
</style>