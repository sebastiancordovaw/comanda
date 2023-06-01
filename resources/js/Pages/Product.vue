
<template>
    <AppLayout title="Dashboard">
        <div class="py-12 bodyApp">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl">
                    <div class="grid grid-cols-12 lg:grid-cols-12">
                        <div  class="pt-2 lg:col-span-2 lg:pt-0 bg-derecho" >
                            <list-categories></list-categories>
                        </div>
                        <div  class="pt-2 lg:col-span-7 lg:pt-0 bg-izquierdo" >
                            <table-products></table-products>
                        </div>
                        <div  class="pt-2 lg:col-span-3 lg:pt-0 bg-derecho" >
                            <form-product></form-product>
                            <form-new-product></form-new-product>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import ListCategories from '@/Pages/Products/ListCategories.vue';
import TableProducts from '@/Pages/Products/TableProducts.vue';
import FormProduct from '@/Pages/Products/FormProduct.vue';
import FormNewProduct from '@/Pages/Products/FormNewProduct.vue';
import {onMounted} from 'vue';
export default {
    setup(){


        onMounted(()=>{
            let heigth = window.screen.height;
            setTimeout(() => {
                for(let i = 0; i<document.getElementsByClassName("bodyApp").length; i++)
                {
                    document.getElementsByClassName("bodyApp")[i].style.minHeight = (heigth - 175)+"px";
                }
            }, 1);
        })

    },
    components:{
        AppLayout,
        ListCategories,
        TableProducts,
        FormProduct,
        FormNewProduct

    }
}
</script>
