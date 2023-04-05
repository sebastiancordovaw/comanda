import {createStore} from 'vuex';
import axios from 'axios';


const store = createStore({

    state:{
        productsSearch  : [],
        tableActivate   : 0, 
        tables          : [],
        cart1           : {},
        products        : [], 
        total_amount_order: 0

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
        },
        setCart1(state,payload){
            state.cart1[state.tableActivate][payload.id] = payload;
            localStorage.setItem("cart1",  JSON.stringify(state.cart1));
            localStorage.setItem("table", state.tableActivate);
        },
        async confirCart1Commit(state)
        {
            await axios.post('/set-order-detail',{key:state.tableActivate, cart:state.cart1[state.tableActivate]},{
                headers: {
                'Content-Type': 'application/json'
                }
              })
            .then(response=>{
               state.products = response.data;
               delete state.cart1[state.tableActivate]
            });
        },
        async getOrderCommit(state)
        {
            await axios.post('/get-order-detail',{table:state.tableActivate})
            .then(response=>{
                
                state.products = response.data;
            });
        },
        async deleteProductOrderCommit(state,payload){
            await axios.post('/delete-order-table',{order_detail:payload.id})
            .then(response=>{
                store.commit('getOrderCommit');
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
                product = {'id':products.id,'name':products.name,'price':products.price,'count':state.cart1[state.tableActivate][products.id].count,'total_amount':null };
                state.cart1[state.tableActivate][products.id] = product;
                state.cart1[state.tableActivate][products.id].count++;
                state.cart1[state.tableActivate][products.id].total_amount= state.cart1[state.tableActivate][products.id].price * state.cart1[state.tableActivate][products.id].count
            }
            else
            {
                product = {'id':products.id,'name':products.name,'price':products.price,'count':1,"total_amount":products.price};
                state.cart1[state.tableActivate][products.id] = product;
            }

            commit('setCart1', state.cart1[state.tableActivate][products.id]);
        },
        confirmCart1Action({commit,state})
        {
            commit('confirCart1Commit');
        },
        getOrder({commit,state}){
            commit('getOrderCommit')
        },
        deleteProductOrder({commit,state},product)
        {
            commit('deleteProductOrderCommit',product);
        }
    },
    getters:{

    }
});

export { store };