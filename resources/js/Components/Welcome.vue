
<template>
    <div class="grid grid-cols-4 gap-4">
        
        <div  class="col-span-2 p-5" >
            <button v-show = "$store.state.tables.length"  v-for="(table, i ) in $store.state.tables" :key="i"  :class="table.status == 1 ?'open':''"  :id="'table_'+table.id"   @click = "selectTable(table)" class="float-left w-24 h-24 mr-4 text-3xl text-center text-white bg-green-500 border-2 border-green-700 rounded-md hover:bg-green-400 tables">{{table.number}}</button>
        </div>
        <div class="col-span-2">
            <change-table></change-table>
            <form-table></form-table>
        </div>
    </div>
</template>

<script>
import FormTable from '@/Custom/FormTable.vue';
import ChangeTable from '@/Custom/ChangeTable.vue';
import { onMounted, onUpdated } from 'vue';
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
            //store.dispatch('getTables')

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
                    for(let i = 0 ; i < store.state.tables.length; i++){
                        
                        pusher.subscribe('public-table-'+store.state.tables[i]['id'])
                        .bind('table-'+store.state.tables[i]['id'], function(data) {
                            document.getElementById("table_"+store.state.tables[i].id).classList.add("open");
                            if(store.state.tables[i].id == store.state.tableActivate)
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

                        pusher.subscribe('public-close-table-'+store.state.tables[i]['id'])
                        .bind('close-table-'+store.state.tables[i]['id'], function(data) {
                            document.getElementById("table_"+store.state.tables[i].id).classList.remove("open");
                            if(store.state.tables[i].id == store.state.tableActivate)
                            {
                                store.dispatch("clearFixAction");
                            }
                             pusher.unsubscribe('public-order-'+data.order)
                            .unbind('order-'+data.order);
                        });
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

        onUpdated(()=>
        {
            if(document.getElementById("table_"+localStorage.getItem("table"))!=null)
            {
                for(let i = 0; i< document.getElementsByClassName("tables").length; i++)
                {
                    document.getElementsByClassName("tables")[i].classList.remove("selectTable");
                }

                console.log(localStorage.getItem("table"));
                document.getElementById("table_"+localStorage.getItem("table")).classList.add("selectTable");
            }
        })
        

        return {selectTable}
    }
    ,
    components:{
        FormTable,
        ChangeTable
    },
    methods:{
        
    },
    created(){
      
        
    }
}
</script>
<style>
.selectTable{
    -webkit-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
-moz-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
}
.selectTable:hover{
    -webkit-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
-moz-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
}
.open{
background-color: #e74c3c;
border-color: #c0392b;
}

.open:hover{
background-color: #ff7979;
}
</style>