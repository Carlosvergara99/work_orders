import operator from './components/operator/view';

import order from './components/order/view';

export const routes = [
    
    
    {
        name: 'order',
        path: '/',
        component: order
    },
    {
        name: 'operator',
        path: '/operator',
        component: operator
    },
    {
        path: '*',
        component: order
    }

   
]