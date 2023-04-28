import Welcome from '@/Components/Welcome.vue';
//import Other from '@/Custom/Other.vue';
import {createRouter, createWebHashHistory} from 'vue-router'
const routes = [
    {path:'/dashboard', name:'Dashboard', component:Welcome},
   // {path:'/other', name:'Other', component:Other},
];

const router = createRouter({
    history:createWebHashHistory(),
    routes
})


export default router;