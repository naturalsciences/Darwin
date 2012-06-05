---
layout: page
title: News
menu: news
---

{% include JB/setup %}
{% for post in site.categories.news %}
<article class="post">
  <header class="aricle-header">
    <p class="date">
      <span class="big">{{ post.date | date: "%d" }}</span>
      <span class="small">{{ post.date | date: "%b" }}</span>
    </p>
    <h2><a href="{{ BASE_PATH }}{{ post.url }}">{{ post.title }}</a></h2>
  </header>
  <div class="row-fluid">
    {{ post.content }}
  </div>
</article>
{% endfor %}
