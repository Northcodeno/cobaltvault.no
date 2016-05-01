from django.conf.urls import url
from django.contrib.auth.views import password_reset, password_reset_confirm

from . import views

urlpatterns = [
	url(r'^reset$', password_reset, { 
			'template_name': 'info/reset.html', 
			'email_template_name': 'info/mail/reset_plain.txt', 
			'subject_template_name': 'info/mail/reset_subj.txt', 
			'html_email_template_name': 'info/mail/reset.html', 
			'post_reset_redirect': 'password_reset_sent'
		}, name="password_reset"),
	url(r'^reset_sent$', views.password_reset_sent, name="password_reset_sent"),
	url(r'^password_reset/(?P<uidb64>[0-9A-Za-z]+)-(?P<token>[0-9A-Za-z-]+)$', password_reset_confirm, {'template_name': 'info/reset.html'}, name="password_reset_confirm"),
	url(r'^password_reset_complete$', views.password_reset_complete, name="password_reset_complete"),
]
