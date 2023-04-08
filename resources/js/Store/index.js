import {createStore} from 'vuex';
import axios from 'axios';


const store = createStore({

    state:{
        productsSearch  : [],
        tableActivate   : 0,
        tableActivateNumber : 0,
        showDiscount        : false,
        tables          : [],
        cart1           : {},
        products        : [], 
        total_amount_order: 0,
        tip               :0,
        discount          :0,
        porcentage        :0,
        discount_active          :0,
        percentage_active      :0,
    },
    mutations:{
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
            await axios.post('/get-order-detail',{table:state.tableActivate})
            .then(response=>{
                state.products                  = response.data.detail;
                if(response.data.discount!=null)
                {
                    state.percentage_active     =  response.data.discount.percentage;
                    state.discount_active       =  response.data.discount.discount;
                }
            })
                
        },
        async deleteProductOrderCommit(state,payload){
            await axios.post('/delete-order-table',{order_detail:payload.id})
            .then(response=>{
                store.commit('getOrderCommit');
            });
        },
        async applyDiscountCommit(state){

            state.showDiscount = false;
            state.discount_active=state.discount;
            await axios.post('/apply-discount',{'discount':state.discount,'porcentage':state.porcentage,'table':state.tableActivate})
            .then(response=>{
                store.commit('getOrderCommit');
            });

        },
        async cancelDiscountCommit(state)
        {
            state.showDiscount = false;
            await axios.post('/get-discount',{'table':state.tableActivate})
            .then(response=>{
                state.percentage_active = response.data.percentage;
                state.discount_active = response.data.discount;
            });
        },
        async deleteDiscountCommit(state){
            await axios.post('/delete-discount',{'table':parseInt(state.tableActivate)})
            .then(response=>{
                state.percentage= 0;
                state.discount= 0;
                state.percentage_active = 0;
                state.discount_active = 0;
                store.commit('getOrderCommit');
            });
        },
        async closeOrderCommit(state){
            await axios.post('/close-order',{'table':parseInt(state.tableActivate),'tip':state.tip})
            .then(response=>{


                for(let i = 0; i< state.tables.length; i++){
                    if(state.tableActivate ==  state.tables[i].id)
                    {
                        state.tables[i].status = 0
                    }
                }

                state.productsSearch  = [],
                state.tableActivateNumber = 0,
                state.showDiscount        = false;
                delete state.cart1[state.tableActivate];
                state.products        = []; 
                state.total_amount_order = 0,
                state.tip               =0;
                state.discount          =0;
                state.porcentage        =0;
                state.discount_active   =0;
                state.percentage_active =0;
                state.tableActivate   = 0;

            });
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
        deleteDiscountAction({commit,state}){
            commit('deleteDiscountCommit') 
        },
        closeOrderAction({commit,state}){
            commit('closeOrderCommit') 
        }
        
    },
    getters:{

    }
});

export { store };