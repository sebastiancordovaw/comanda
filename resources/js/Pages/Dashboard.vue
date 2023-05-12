<template>
    <AppLayout title="Dashboard" v-show="!openSwitch">

        <!----
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>
        ----->
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
    <add-product v-if="openSwitch" @closeAddProductMobile="closeAddProductMobile"></add-product>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import AddProduct from '@/Custom/Mobile/AddProduct.vue';
import EventBus from '../EventBus.js';
import { ref,onMounted, onUpdated } from 'vue';

const openSwitch = ref(false);
export default{
    setup(){

        EventBus.on("addProductMobile",()=>{
            openSwitch.value = !openSwitch.value;
        })

        const closeAddProductMobile = () => {
            openSwitch.value = !openSwitch.value;
        }

        return{
            openSwitch,closeAddProductMobile
        }
    },
    components:{
        AppLayout,
        Welcome,
        AddProduct,

    }
}
</script>

        AddProduct
