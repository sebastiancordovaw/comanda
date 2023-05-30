<template>
    <AppLayout title="Dashboard" v-show="!openSwitch">

        <!----
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-whte">
                Dashboard
            </h2>
        </template>
        ----->
        <div class="py-12 bodyApp">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl">
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
import { ref,onMounted, onUpdated, onUnmounted } from 'vue';

const openSwitch = ref(false);
export default{
    setup(){

        EventBus.on("addProductMobile",()=>{
            openSwitch.value = true ;
        })

        const closeAddProductMobile = () => {
            openSwitch.value = !openSwitch.value;
        }

        onUpdated(()=>{
            let heigth = window.screen.height;
            setTimeout(() => {
                for(let i = 0; i<document.getElementsByClassName("bodyApp").length; i++)
                {
                    document.getElementsByClassName("bodyApp")[i].style.minHeight = (heigth - 175)+"px";
                }
            }, 1);
        })

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
