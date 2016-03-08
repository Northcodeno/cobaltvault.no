from django.utils.translation import ungettext_lazy
from django.shortcuts import render
from django.http import Http404
from django.views.generic.list import ListView
from django.utils.encoding import smart_str
from django.db.models import Q
from django.shortcuts import get_object_or_404
from django_tables2 import RequestConfig
from django.contrib.auth import login
from django.contrib.auth import logout
from django.contrib.auth import authenticate
from django.contrib.auth.forms import AuthenticationForm
from django.contrib import messages
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect
from django import forms

from pprint import pprint
import sys

from .models import Project
from .tables import ProjectTable
from .forms import RegForm
from info.models import NewsPost

# Create your views here.
def index(request):
	latest = Project.objects.order_by('-date_modified')[:5]
	mostdl = Project.objects.order_by('-downloads')[:5]
	news = NewsPost.objects.all()[:5]
	return render(request, "mainpage/index.html", { 'latest': latest, 'mostdl': mostdl, 'news': news })

def list(request):
	table = ProjectTable(Project.objects.all())
	table.paginate(page=request.GET.get('page',1), per_page=25)
	RequestConfig(request).configure(table)
	return render(request, "mainpage/list.html", {'table': table })

def project(request, project_id):
	if (project_id.isdigit()):
		project = get_object_or_404(Project, pk=project_id)
	else:
		project = get_object_or_404(Project, idname=project_id)
	return render(request, "mainpage/project.html", {'project': project})

def project_download(request, project_id):
	return
	#project = get_object_or_404(Project, Q(pk=project_id)|Q(idname=project_id))
	
	#response = HttpResponse(mimetype='application/force-download')
	#response['Content-Disposition'] = 'attachment; filename=%s' % smart_str()

def register_view(request):
	if request.method == 'POST':
		form = RegForm(data=request.POST)
		try:
			form.is_valid()
			form.clean()
			form.save()
			messages.success(request, 'You have successfully registered')
			return HttpResponseRedirect(reverse('index'))
		except forms.ValidationError as err:
			messages.error(request, err)
	form = RegForm()
	return render(request, "mainpage/register.html", {'form': form})

def login_view(request):
	if request.user.is_authenticated():
		messages.info(request, 'You are already logged in')
	else:
		form = AuthenticationForm(data=request.POST)
		try:
			form.is_valid()
			form.clean()
			user = form.get_user()
			login(request, user)
			messages.success(request, 'You have logged in')
		except forms.ValidationError as err:
			messages.error(request, str(err)[2:-2])
		except AttributeError:
			messages.error(request, 'Could not log you in')
	

	return HttpResponseRedirect(request.META.get('HTTP_REFERER', '/'))

def logout_view(request):
	if not request.user.is_authenticated():
		messages.info(request, 'You are not logged in')
	else:
		logout(request)
		messages.info(request, 'You have been logged out')
	return HttpResponseRedirect(request.META.get('HTTP_REFERER'))