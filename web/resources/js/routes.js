import Homepage from './components/pages/homepage/Homepage.vue';
import Candidates from './components/pages/candidates/Candidates.vue';

const routes = [
    {
        path: '/',
        name: 'homepage',
        component: Homepage
    },
    {
        path: '/candidates-list',
        name: 'candidates',
        component: Candidates
    },
    {
        path: '*',
        redirect: '/',
    },
];

export default routes;
