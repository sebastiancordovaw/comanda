import axios from 'axios';
export default class NodeService {

    getTreeNodes() {
        return axios.post('/get-CategoriesTree').then(res => res.data.root);
    }

}
