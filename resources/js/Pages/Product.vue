
<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl">
                    <div class="grid grid-cols-12 lg:grid-cols-12">
                        <div  class="pt-2 lg:col-span-2 lg:pt-0" >
                            <list-categories></list-categories>
                        </div>
                        <div  class="pt-2 lg:col-span-5 lg:pt-0" >
                            <table-products></table-products>
                        </div>
                        <div  class="pt-2 lg:col-span-5 lg:pt-0" >
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

export default {
    setup(){

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
