
<template>

<div>
    <div class="grid grid-cols-12">
        <div  class="col-span-12" >
            <ul class="relative hidden pt-3 pl-3 bg-gray-800 rooms-movil">
                <li  @click="zone(i)" :class="i" class="float-left p-3 py-3 mr-2 text-white bg-gray-600 cursor-pointer rounded-t-sm space hover:bg-gray-500"  v-for="(table, i ) in $store.state.tables" :key="i">{{ i }} <span :class="'total_tables_'+i">0</span></li>
                <div class="clear-both"></div>
            </ul>
        </div>
    </div>
    <div class="grid grid-cols-12 lg:grid-cols-7">
        <div  class="col-span-2 lg:col-span-3 pt-2 lg:pt-0 bg-izquierdo" >
            <tables></tables>
        </div>
        <div class="col-span-10 lg:col-span-4 pr-2 pt-2 lg:pr-0 lg:pt-0 bg-derecho">
            <form-table></form-table>
        </div>
    </div>
</div>
</template>

<script>
import FormTable from '@/Custom/FormTable.vue';
import Tables from '@/Custom/Tables.vue';
import { ref,onMounted, onUpdated } from 'vue';
import { useStore } from 'vuex';
export default {
    data(){
        return{

        }
    },
    setup()
    {
        const store = useStore();
        onMounted(()=>{

        });

        onUpdated(()=>
        {
            countOpenTables();
        })

        const zone =(zoneClick)=>{

            if(store.state.zoneActive != zoneClick)
            {
                for(let i =0; i < document.getElementsByClassName("zones").length; i++)
                {
                    document.getElementsByClassName("zones")[i].classList.remove("display");
                }

                for(let i =0; i < document.getElementsByClassName('space').length; i++)
                {
                    document.getElementsByClassName('space')[i].classList.remove('active');
                }
                document.getElementsByClassName(zoneClick)[0].classList.add('active');
                document.getElementById(zoneClick).classList.add("display");

                localStorage.setItem("zone",zoneClick);
                store.state.zoneActive = localStorage.getItem("zone");
            }
        }

        const countOpenTables = () =>{

            for(let i = 0 ; i < Object.keys(store.state.tables).length; i++){
                let count = 0;
                for(let j = 0; j < store.state.tables[Object.keys(store.state.tables)[i]]['tables'].length; j++)
                {
                    let tableFor = store.state.tables[Object.keys(store.state.tables)[i]]['tables'];

                    if(tableFor[j].status == 1)
                    {
                        count++;
                    }
                }
                count = (count==0)?'':count;
                document.getElementsByClassName("total_tables_"+Object.keys(store.state.tables)[i])[0].innerHTML = count;
                document.getElementsByClassName("total_tables_"+Object.keys(store.state.tables)[i])[1].innerHTML = count;
                if(count>0)
                {
                    document.getElementsByClassName("total_tables_"+Object.keys(store.state.tables)[i])[0].classList.add("openTables");
                    document.getElementsByClassName("total_tables_"+Object.keys(store.state.tables)[i])[1].classList.add("openTables");
                }
                else
                {
                    document.getElementsByClassName("total_tables_"+Object.keys(store.state.tables)[i])[0].classList.remove("openTables");
                    document.getElementsByClassName("total_tables_"+Object.keys(store.state.tables)[i])[1].classList.remove("openTables");
                }
            }
        }
        return {zone}
    }
    ,
    components:{
        FormTable,
        Tables
    },
    methods:{

    },
    created(){


    }
}
</script>
<style scoped>
.selectTable{
-webkit-box-shadow: 0px 0px 11px 4px rgba(245,202,10,1);
-moz-box-shadow: 0px 0px 11px 4px rgba(245,202,10,1);
box-shadow: 0px 0px 11px 4px rgba(245,202,10,1);
}
.selectTable:hover{
    -webkit-box-shadow: 0px 0px 11px 4px rgba(245,202,10,1);
-moz-box-shadow: 0px 0px 11px 4px rgba(245,202,10,1);
box-shadow: 0px 0px 11px 4px rgba(245,202,10,1);
}
.open{
background-color: #e74c3c;
border-color: #c0392b;
}

.open:hover{
background-color: #ff7979;
}

.tables>div{
    width: 100%;
    height: 100%;
    padding: 19px 0px 0px 0px;
    cursor:pointer;
}

.tables.open>div{
    border-color:#c0392b;
}

button[role='tab']
{
    border: 0px;
    border-radius: 3px  3px 0px 0px;

}
@media (max-width: 1023px) {
    .tables>div
    {
        padding: 4px 0px 0px 0px;

        font-size:18px;
    }
    .rooms-movil
    {
        display:block !important;
    }
}

.space.active{
    background-color: #BDC3C7;
    color: #2C3E50;
    font-weight: bold;
}

.openTables{
background-color:#ff7979;
color:whitesmoke;
padding:2px 5px;
border-radius: 2px;
}



</style>
