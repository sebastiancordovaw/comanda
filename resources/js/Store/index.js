import {createStore} from 'vuex';
import axios from 'axios';


const store = createStore({

    state:{
        productsSearch  : [],
        zoneActive       : '',
        tableActivate   : 0,
        orderActivate   : 0,
        orders          : [],
        tableActivateNumber : 0,
        showDiscount        : false,
        tables          : [],
        cart1           : {},
        products        : [],
        subtotal_amount_order:0,
        total_amount_order: 0,
        total_amount_order_paid: 0,
        tip               :0,
        discount          :0,
        porcentage        :0,
        discount_active   :0,
        discount_active_percentage :0,
        amount_free:0,
        loadData:false
    },
    mutations:{
        clearFix(state)
        {
            state.productsSearch  = [];
            state.zoneActive       = '',
            //state.tableActivate   = 0;
            state.orderActivate   = 0;
            state.tableActivateNumber = 0;
            state.showDiscount        = false;
            state.cart1           = {};
            state.products        = [];
            state.subtotal_amount_order=0;
            state.total_amount_order= 0;
            state.total_amount_order_paid= 0;
            state.tip               =0;
            state.discount          =0;
            state.porcentage        =0;
            state.discount_active   =0;
            state.discount_active_percentage =0;
            state.amount_free=0;

            //localStorage.setItem("zone",'');
            localStorage.setItem("table",null);
            localStorage.setItem("cart1",{});
            localStorage.setItem("tableNumber",0);

            state.loadData=false;
        },
        setTables(state, payload)
        {
            state.tables = payload;
        },
        setProductsSearch(state, payload)
        {
            if(payload.length >0)
            {
                state.productsSearch = payload;
            }
            else{
                state.productsSearch = []
            }
        },
        setActiveTable(state, payload)
        {
            state.tableActivate = payload.id;
            state.tableActivateNumber = payload.number;
            localStorage.setItem("table", state.tableActivate);
            localStorage.setItem("tableNumber", state.tableActivateNumber);
        },
        setCart1(state,payload){
            state.cart1[state.tableActivate][payload.id] = payload;
            localStorage.setItem("cart1",  JSON.stringify(state.cart1));
        },
        async confirmCart1Commit(state)
        {
            await axios.post('/set-order-detail',{key:state.tableActivate, cart:state.cart1[state.tableActivate]},{
                headers: {
                'Content-Type': 'application/json'
                }
              })
            .then(response=>{
               state.products = response.data;
               delete state.cart1[state.tableActivate]
               localStorage.setItem("cart1",  JSON.stringify(state.cart1));
               store.commit('getOrderCommit');
            });

            for(let i = 0; i< state.tables.length; i++){
                if(state.tableActivate ==  state.tables[i].id)
                {
                    state.tables[i].status = 1
                }
            }

        },
        commentCommit(state,payload){
            state.cart1[state.tableActivate][payload.payload.id].note = payload.comment;
            localStorage.setItem("cart1",  JSON.stringify(state.cart1));

        },
        async getOrderCommit(state)
        {
            /****LLAMADA A LOS DATOS DE ORDEN */
            store.state.loadData = true;
            await axios.post('/get-order',{table:state.tableActivate})
            .then(responseOrder=>{

                state.orderActivate = responseOrder.data.id
                state.discount_active = responseOrder.data.discount;

                /****LLAMADA A LOS DATOS DE DETAIL */
                axios.post('/get-order-detail',{table:state.tableActivate})
                .then(responseDetail=>{
                    state.products = responseDetail.data;
                    store.commit('calculateAmountCommit');
                    store.state.loadData = false;
                })

            });


        },
        calculateAmountCommit(state)
        {
            /****resta de todos los valores */
            state.total_amount_order = 0;
            /****el valor neto de los productos */
            state.subtotal_amount_order =0;
            /*****el valor del procentaje aprlicado */
            state.discount_active_percentage = 0;
            /****el valor libre sin porcentaje y sin estar pagado */
            state.amount_free=0;
            /****el valor de los productos pagados */
            state.total_amount_order_paid = 0;
            /****valor de la propina */
            state.tip = 0;
            /******** valor de contador de los productos sin pagar */

            if(state.products.length > 0)
            {
                for(let i = 0; i< state.products.length; i++)
                {
                    if( state.products[i].status > 0)
                    {
                        /**** obtengo el total limpio de los productos agregados**** */
                        state.subtotal_amount_order += state.products[i].amount;

                        if(state.products[i].date_pay == null)
                        {
                            if(state.products[i].percentage>0)
                            {
                                /***funcion para calcular el total con el descuento en porcentage***/
                                state.discount_active_percentage +=  ((state.products[i].amount * state.products[i].percentage )/ 100);

                                /****************aqui se calcula lo que mostrara com redultado de la suma de
                             * los produtos con el procetaje aplicado */
                                state.total_amount_order += state.products[i].amount - ((state.products[i].amount * state.products[i].percentage )/ 100);
                            }
                            else
                            {


                                    state.total_amount_order += state.products[i].amount
                                    state.amount_free += state.products[i].amount
                            }
                        }
                        else
                        {
                            state.tip += state.products[i].tip;
                            //state.total_amount_order += state.products[i].amount
                            //state.discount_active_percentage += (state.products[i].amount - ((state.products[i].amount * state.products[i].percentage )/ 100));
                            state.total_amount_order_paid += state.products[i].amount - ((state.products[i].amount * state.products[i].percentage )/ 100);
                        }

                    }

                }

                if(state.discount_active_percentage>0)
                {
                     /********************mustra el total de porcentage descontado */
                    //state.discount_active_percentage = state.subtotal_amount_order - state.discount_active_percentage - state.amount_free - state.total_amount_order_paid;
                    console.log(state.total_amount_order,'total_amount_order');

                    console.log(state.subtotal_amount_order,'subtotal_amount_order');

                    console.log(state.discount_active_percentage ,'discount_active_percentage');

                    console.log(state.amount_free,'amount_free');

                    console.log(state.total_amount_order_paid,'total_amount_order_paid');

                }

                if(state.discount_active>0)
                {
                    /********************para mostrar el total restando el descuento fijo */
                    state.total_amount_order = state.total_amount_order - state.discount_active;
                }

            }
        },
        async deleteProductOrderCommit(state,payload){
            await axios.post('/delete-order-table',{order_detail:payload.id})
            .then(response=>{
                state.loadData = false;
                store.commit('getOrderCommit');
            });
        },
        async deleteProductOrderPaidCommit(state,payload){
            await axios.post('/delete-order-paid-table',{order_detail:payload.id})
            .then(response=>{
                state.loadData = false;
                store.commit('getOrderCommit');
            });
        },
        async applyDiscountCommit(state){

            state.showDiscount = false;
            if(state.porcentage == 0)
            {
                state.discount_active=state.discount;
            }

            await axios.post('/apply-discount',{'percentage':state.porcentage,'discount':state.discount,'table':state.tableActivate})
            .then(response=>{
                state.loadData = false;
                store.commit('getOrderCommit');
            });

        },
        async cancelDiscountCommit(state)
        {
            state.showDiscount = false;
            state.loadData = false;
        },

        async closeOrderCommit(state,payload){
            await axios.post('/close-order',{'table':parseInt(state.tableActivate ),'order':parseInt(state.orderActivate),'tip':payload})
            .then(response=>{
                for(let i = 0; i< state.tables.length; i++){
                    if(state.tableActivate ==  state.tables[i].id)
                    {
                        state.loadData = false;
                        state.tables[i].status = 0
                    }
                }

                store.commit('clearFix');
            });
         },
         async closeOrderCheckCommit(state, payload){
            await axios.post('/close-order-check',{'ids':payload.ids,'tip':payload.tip})
            .then(response=>{
                state.loadData = false;
                store.commit('getOrderCommit');
            })
         },
         async closeOrderCheckFinallyCommit(state, payload){
            await axios.post('/close-order-check-finally',{'table':payload.table,'order':payload.order})
            .then(response=>{

                for(let i = 0; i< state.tables.length; i++){
                    if(state.tableActivate ==  state.tables[i].id)
                    {
                        state.loadData = false;
                        state.tables[i].status = 0
                    }
                }

                store.commit('clearFix');
            })
         },
         async changeTableCommit(state,payload)
         {
            await axios.post('/change-table',{'newTable':payload.newTable,'oldTable':payload.oldTable})
            .then(response=>{
                if(response.data){

                    state.tableActivate   = payload.newTable;
                    state.tableActivateNumber   = response.data;
                    localStorage.setItem("tableNumber", state.tableActivateNumber)
                    for(let i = 0; i< state.tables.length; i++){
                        if(payload.newTable ==  state.tables[i].id)
                        {
                            state.tables[i].status = 1
                        }

                        if(payload.oldTable ==  state.tables[i].id)
                        {
                            state.tables[i].status = 0
                        }
                    }
                    store.commit('getOrderCommit');
                }

            });
         },
         async deleteDiscountPermanentCommit(state)
         {
            await axios.post('/delete-discount-permanent',{'order':parseInt(state.orderActivate)})
            .then(response=>{

                state.loadData = false;

            });
         },
         async deleteDiscountPercentageCommit(state)
         {
            await axios.post('/delete-discount-percentage',{'order':parseInt(state.orderActivate)})
            .then(response=>{
                store.commit('getOrderCommit');
                state.loadData = false;
            });
         },
         async delDiscountProductCommit(state, payload){
            await axios.post('/delete-discount-product',{'product':parseInt(payload)})
            .then(response=>{
                state.loadData = false;
                store.commit('getOrderCommit');
            });
         },
         async addDiscountProductCommit(state, payload)
         {
            await axios.post('/add-discount-product',{'product':payload.product.id,'discount':payload.discount})
            .then(response=>{
                state.loadData = false;
                store.commit('getOrderCommit');
            })
         }

    },
    actions:{
        async getProducts ({commit,state},search){

            try{
                if(search.trim().length>0)
                {
                    await axios.post('/get-products',{search})
                    .then(response=>{
                        commit('setProductsSearch',response.data);
                    });
                }
                else{
                    commit('setProductsSearch',[]);
                }

            }
            catch(error)
            {

            }
        },
        async getTables ({commit,state}){

            try{
                await axios.post('/get-tables')
                .then(response=>{

                    commit('setTables',response.data);

                });
            }
            catch(error)
            {
                console.log(error);
            }
        }
        ,
        setActive({commit,state},table)
        {
            commit('setActiveTable',table);
        },
        addCart1Action({commit,state},products){
            let product = {};

            if(state.cart1[state.tableActivate] === undefined)
            {
                state.cart1[state.tableActivate] = new Object();
            }

            if(state.cart1[state.tableActivate].hasOwnProperty(products.id))
            {
                product = {'id':products.id,'name':products.name,'price':products.price,'count':state.cart1[state.tableActivate][products.id].count,"note":state.cart1[state.tableActivate][products.id].note,'total_amount':null };
                state.cart1[state.tableActivate][products.id] = product;
                state.cart1[state.tableActivate][products.id].count++;
                state.cart1[state.tableActivate][products.id].total_amount= state.cart1[state.tableActivate][products.id].price * state.cart1[state.tableActivate][products.id].count
            }
            else
            {
                product = {'id':products.id,'name':products.name,'price':products.price,'count':1,"note":products.note,"total_amount":products.price};
                state.cart1[state.tableActivate][products.id] = product;
            }

            state.cart1[state.tableActivate][products.id].total_amount;

            commit('setCart1', state.cart1[state.tableActivate][products.id]);
        },
        confirmCart1Action({commit,state})
        {
            commit('confirmCart1Commit');
        },
        getOrder({commit,state}){
            commit('getOrderCommit')
        },
        deleteProductOrder({commit,state},product)
        {
            commit('deleteProductOrderCommit',product);
        },
        deleteProductOrderPaid({commit,state},product)
        {
            commit('deleteProductOrderPaidCommit',product);
        },
        commentAction({commit,state},data)
        {
            commit('commentCommit',{payload:data.product,comment:data.comment});
        },
        applyDiscountAction({commit,state}){
            commit('applyDiscountCommit')
        },
        cancelDiscountAction({commit,state}){
            commit('cancelDiscountCommit')
        },
        deleteDiscountPermanentAction({commit,state}){
            commit('deleteDiscountPermanentCommit')
        },
        deleteDiscountPercentageAction({commit,state}){
            commit('deleteDiscountPercentageCommit')
        },
        closeOrderAction({commit,state},tip){
            commit('closeOrderCommit',tip)
        },
        closeOrderCheckAction({commit,state},data)
        {
            commit('closeOrderCheckCommit',data);
        },
        closeOrderCheckFinallyAction({commit,state},data)
        {
            commit('closeOrderCheckFinallyCommit',data);
        },
        changeTableAction({commit,state},tables)
        {
            commit('changeTableCommit',{'newTable':tables.newTable, 'oldTable':tables.oldTable})
        },
        calculateAmountAction({commit,state},tables)
        {
            commit('calculateAmountCommit')
        },
        delDiscountProductAction({commit,state},product){
            commit('delDiscountProductCommit',product)
        },
        addDiscountProductAction({commit,state},product)
        {
            commit('addDiscountProductCommit',product);
        },
        clearFixAction({commit,state})
        {
            commit('clearFix');
        }
    },
    getters:{

    }
});

export { store };
