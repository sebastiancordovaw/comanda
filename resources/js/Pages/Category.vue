<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl">
                    <div class="grid grid-cols-12 lg:grid-cols-12">
                        <div  class="col-span-2 pt-2 lg:col-span-7 lg:pt-0" >
                            <table-category  @sendCategory = "sendCategory" @sendCategories = "sendCategories" @addCategory = "addCategory" @deleteCategory =  "deleteCategory" :updateListCategory="listCategories"></table-category>
                        </div>
                        <div class="col-span-10 lg:col-span-5" >
                            <transition name="slide-fade">
                                <div v-if="category" >
                                    <form-category  @updateList = "updateList" :category="category" :selectCategories="selectCategories" @idRow = "idRow"></form-category>
                                </div>
                            </transition>
                            <transition name="slide-fade">
                                <div v-if="newCategoryComponent" >
                                    <form-new-category  @updateList = "updateList" :selectCategories="selectCategories" @idRow = "idRow"></form-new-category>
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
import {ref, onMounted, onUpdated} from 'vue'
const categories = ref(null);
const category = ref('');
const newCategoryComponent = ref(false);
const selectCategories = ref('');
const listCategories = ref('');
const affectRow = ref(0);
import EventBus from "../EventBus.js";

export default {
    setup(){
        onUpdated(()=>{

        });

        const updateList = (updateList) =>{
            EventBus.emit('UpdateEventList');
            EventBus.emit('affectRow',affectRow.value);
        }

        const sendCategory = (selectCategory) =>{

            if(category.value.id!=selectCategory.id)
            {
                category.value = '';
                setTimeout(() => {
                    category.value = selectCategory;
                }, 1);
            }
            newCategoryComponent.value = false;
        }

        const sendCategories =(categories) =>{
            selectCategories.value = categories;
        }

        const idRow =(row) =>{

            if(typeof row === 'object')
            {
                affectRow.value = row.id;
                category.value = row;
                newCategoryComponent.value = false;
            }
            else
            {
                affectRow.value = row;
            }
        }

        const addCategory = ()=>{
            if(newCategoryComponent.value === false)
            {
                newCategoryComponent.value = true;
            }
            category.value = '';
        }

        const deleteCategory = ()=>{
            addCategory();
        }


        return {
            sendCategory,
            sendCategories,
            selectCategories,
            updateList,
            deleteCategory,
            listCategories,
            category,
            addCategory,
            newCategoryComponent,
            idRow,
            affectRow
        }

    },
    components:{
        AppLayout,
        FormCategory,
        FormNewCategory,
        TableCategory,
        affectRow
    }
}
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}



.slide-fade-enter-from
{
  transform: translateX(60px);
  opacity: 0;
}

</style>
