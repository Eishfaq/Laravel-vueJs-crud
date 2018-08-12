<template>
    <div>
        <h1>Articles</h1>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Search" @keyup="dosearch(this.values)">
            <ul v-if="article.length > 0">
                <li v-for="result in article" :key="result.id" v-text="result.title"></li>
            </ul>
        </div>
        <form @submit.prevent="addArticle" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Enter Title" v-model="article.title">
            </div>
            <div class="form-group">
                <textarea type="text" class="form-control" placeholder="Enter Content" v-model="article.content"></textarea>
            </div>

            <button class="btn btn-primary">Save</button>
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchArticles(pagination.prev_page_url)">Previous</a></li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchArticles(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
        <div class="card card-body col-md-6" v-for="row in articles">
            <h3>{{row.title }}</h3>
            <p>{{ row.content }}</p>
            <p><small>Created: {{row.created_at}} | Updated: {{row.updated_at}}</small></p>
            <button @click="editArticle(row)" class="btn btn-success mb-2">Edit</button>
            <button @click="deleteArticle(row.id)" class="btn btn-danger btn-sm">Delete</button>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return{
                articles: [],
                article:{
                    id: '',
                    title: '',
                    content: ''
                },
                pagination : {},
                edit: false
            }
        },
        created(){
            this.fetchArticles();
        },
        methods:{
            fetchArticles(page_url){
                let vm = this;
                page_url = page_url || '/api/article';
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.articles = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            deleteArticle(id){
                if(confirm('Are You Sure?')){
                    fetch(`api/article/${id}`,{
                        method: 'delete'
                    })
                        .then(res => res. json())
                        .then(data => {
                            alert('Article Removed');
                            this.fetchArticles();
                        })
                        .catch(err => console.log(err));
                }
            },
            addArticle(){
                if(this.edit === false){
                    fetch(`api/article`,{
                        method: 'post',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.article.title = '';
                            this.article.content = '';
                            alert('Article Added');
                            this.fetchArticles();
                        })
                }
                else{
                    // Update
                    fetch('api/article', {
                        method: 'put',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.article.title = '';
                            this.article.content = '';
                            alert('Article Updated');
                            this.fetchArticles();
                        })
                        .catch(err => console.log(err));
                }
            },
            editArticle(article) {
                this.edit = true;
                this.article.id = article.id;
                this.article.article_id = article.id;
                this.article.title = article.title;
                this.article.content = article.content;
            },
            dosearch(value){
                console.log('getit');
            }
        }

    }
</script>