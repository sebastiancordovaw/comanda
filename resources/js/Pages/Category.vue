<template>
    <AppLayout title="Dashboard">
        <div class="py-12 bodyApp">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl">
                    <div class="grid grid-cols-12 lg:grid-cols-12">
                        <div  class="col-span-2 pt-2 lg:col-span-7 lg:pt-0 bg-izquierdo" >
                            <table-category ></table-category>
                        </div>
                        <div class="col-span-10 lg:col-span-5" >
                            <transition name="slide-fade">
                                <div>
                                    <form-category></form-category>
                                </div>
                            </transition>
                            <transition name="slide-fade">
                                <div >
                                    <form-new-category></form-new-category>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormCategory from '@/Pages/Categories/FormCategory.vue';
import FormNewCategory from '@/Pages/Categories/FormNewCategory.vue';
import TableCategory from '@/Pages/Categories/TableCategory.vue';
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
        FormCategory,
        FormNewCategory,
        TableCategory,
    }
}
</script>
