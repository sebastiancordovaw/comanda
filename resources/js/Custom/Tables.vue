<template>
    <ul class="relative pt-3 pl-3 bg-gray-800 rooms">
        <li  @click="zone(i)" :class="i" class="float-left p-3 py-3 mr-2 text-white bg-gray-600 cursor-pointer rounded-t-md space hover:bg-gray-500"  v-for="(table, i ) in $store.state.tables" :key="i">{{ i }} <span :class="'total_tables_'+i">0</span></li>
        <div class="clear-both"></div>
    </ul>
    <div v-for="(data_table, i ) in $store.state.tables" :key="i" :id="i" :class="i" class="hidden zones" >
        <a  v-for="(table, i ) in data_table['tables']" :key="i" :class="table.status == 1 ?'open':''"  :id="'table_'+table.id"   @click = "selectTable(table)" class="float-left w-12 h-12 mb-2 ml-2 mr-2 text-3xl text-center text-white bg-green-500 border-2 border-green-700 rounded-md lg:mt-4 lg:w-20 lg:h-20 hover:bg-green-400 tables">
            <div class="rounded-b-md" >{{table.number}}</div>
        </a>
    </div>
</template>

<script>
import { useStore } from 'vuex';
import { onMounted, onUpdated } from 'vue';
export default {
    data()
    {
        return {

        }
    },
    setup()
    {
        const store = useStore();

        onMounted(()=>{
            const pusher = new Pusher('97f3f1688080565b48cf', {
                cluster: 'us2'
            });
            getTables(pusher);
            eventOrders(pusher);
        });

        const getTables = async(pusher) =>  
        {
            
            try{
                await axios.post('/get-tables')
                .then(response=>{
                    store.state.tables = response.data;
                    for(let i = 0 ; i < Object.keys(store.state.tables).length; i++){
                        for(let j = 0; j < store.state.tables[Object.keys(store.state.tables)[i]]['tables'].length; j++)
                        {
                            let tableFor = store.state.tables[Object.keys(store.state.tables)[i]]['tables'];

                            pusher.subscribe('public-table-'+tableFor[j]['id'])
                            .bind('table-'+tableFor[j]['id'], function(data) {
                                document.getElementById("table_"+tableFor[j].id).classList.add("open");
                                if(tableFor[j].id == store.state.tableActivate)
                                {
                                    store.dispatch('getOrder');
                                }
                                store.state.tableActivateNumber = data.number;
                                pusher.subscribe('public-order-'+data.order)
                                .bind('order-'+data.order, function(data2) {
                                    if(data2.order == store.state.orderActivate) 
                                    {
                                        store.dispatch('getOrder');
                                    }
                                });
                                
                                store.dispatch('getTables');
                                
                            });

                            pusher.subscribe('public-close-table-'+tableFor[j]['id'])
                            .bind('close-table-'+tableFor[j]['id'], function(data) {
                                document.getElementById("table_"+tableFor[j].id).classList.remove("open");
                                store.dispatch('getTables');
                                if(tableFor[j].id == store.state.tableActivate)
                                {
                                    store.dispatch("clearFixAction");
                                }
                                pusher.unsubscribe('public-order-'+data.order)
                                .unbind('order-'+data.order);
                            });
                        }
                    }
                });  
            }
            catch(error)
            {
                console.log(error);
            }
        }

        const eventOrders = async (pusher) => 
        {
            await axios.post('/get-orders-actives')
            .then(response=>
            {
                let orders= response.data;
                
                for(let i = 0; i < orders.length; i++)
                {
                    store.state.orders.push(orders[i].id);
                    pusher.subscribe('public-order-'+orders[i].id)
                    .bind('order-'+orders[i].id, function(data) {
                        console.log(data.order ,store.state.orderActivate);
                        if(data.order == store.state.orderActivate) 
                        {
                            store.dispatch('getOrder');
                        }
                    });
                }
            });  
        }

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

        const selectTable = table =>{
            
            for(let i = 0; i<document.getElementsByClassName("tables").length; i++){
                document.getElementsByClassName("tables")[i].classList.remove("selectTable")
            }
            
            document.getElementById("table_"+table.id).classList.add("selectTable");
            
            store.dispatch('setActive', table);
    
            store.dispatch('getOrder');

            store.state.discount          =0;
            store.state.porcentage        =0;
            store.state.showDiscount = false;

        }

        onUpdated(()=>{
            
            initZones(localStorage.getItem("zone"));
            countOpenTables();
            if(document.getElementById("table_"+localStorage.getItem("table"))!=null)
            {
                for(let i = 0; i< document.getElementsByClassName("tables").length; i++)
                {
                    document.getElementsByClassName("tables")[i].classList.remove("selectTable");
                }

                console.log(localStorage.getItem("table"));
                document.getElementById("table_"+localStorage.getItem("table")).classList.add("selectTable");
            }
            

        });

        const initZones = (zoneInitial) =>{
            zoneInitial= (zoneInitial)? zoneInitial : Object.keys(store.state.tables)[0] ;
            console.log(zoneInitial);
            document.getElementsByClassName(zoneInitial)[0].classList.add('active');
            document.getElementById(zoneInitial).classList.add("display");
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

        return {zone, selectTable}
    }
}
</script>
<style scoped>
.zones.display{
    
  display: block !important;  

}
.space.active{
    background-color: #BDC3C7;
    color: #2C3E50;
    font-weight: bold;
}
.openTables{
    border:solid 1px;
    border-color:#c0392b;
    color:#c0392b;
    padding:2px 5px;
    border-radius: 2px;
}

@media (max-width: 1023px) {
    .rooms
    {
        display:none;
    }
}
</style>