import hashlib
import os
import random
import sys
import uuid
from pprint import pprint

from django import forms
from django.contrib import messages
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.forms import AuthenticationForm
from django.contrib.auth.models import User
from django.core.urlresolvers import reverse
from django.db.models import Q
from django.http import Http404, HttpResponse, HttpResponseRedirect
from django.shortcuts import get_object_or_404, render
from django.utils.encoding import smart_str
from django.utils.translation import ungettext_lazy
from django.views.generic.list import ListView
from django_tables2 import RequestConfig
from info.forms import NewsForm
from info.models import NewsPost
from wsgiref.util import FileWrapper

from .forms import CommentForm, CreateForm, ProfileForm, ProjectForm, RegForm
from .models import Comment, Project, RegUser
from .tables import ProjectTable, UserProjectTable
from .util import safeRedirect


# Create your views here.
def index(request):
	latest = Project.objects.order_by('-date_modified', '-date_created')[:5]
	mostdl = Project.objects.order_by('-downloads')[:5]
	news = NewsPost.objects.order_by('-id')[:5]

	context = { 'latest': latest, 'mostdl': mostdl, 'news': news }

	if request.user.is_authenticated() and request.user.is_staff:
		context['newsform'] = NewsForm()
		if request.method == 'POST':
			newsform = NewsForm(data=request.POST)
			if newsform.is_valid():
				newsform.clean()
				newsform.save()
			else:
				context['newsform'] = newsform

	return render(request, "mainpage/index.html", context)

def list(request):
	table = ProjectTable(Project.objects.filter(ispublic=True))
	table.paginate(page=request.GET.get('page',1), per_page=25)
	RequestConfig(request).configure(table)
	return render(request, "mainpage/list.html", {'table': table })

def project(request, project_id):
	if (project_id.isdigit()):
		project = get_object_or_404(Project, pk=project_id)
	else:
		project = get_object_or_404(Project, idname=project_id)

	context = {'project': project, 'isauthor': False}

	if request.user.is_authenticated():
		if request.method == 'POST':
			context['form'] = CommentForm(data=request.POST)
			if context['form'].is_valid():
				context['form'].clean()
				replyto = None
				if request.POST['replyto']:
					replyto = Comment.objects.get(pk=request.POST['replyto'])
				context['form'].save(user=request.user, project=project, replyto=replyto)
				messages.success(request, 'Your comment has been submitted')
		context['form'] = CommentForm()

		if request.user.project_set.filter(pk=project.pk).exists():
			context['isauthor'] = True

	context['comments'] = Comment.objects.filter(project=project, replyto=None)

	return render(request, "mainpage/project.html", context)

def project_edit(request, project_id):
	if (project_id.isdigit()):
		project = get_object_or_404(Project, pk=project_id)
	else:
		project = get_object_or_404(Project, idname=project_id)
	if not request.user.is_authenticated or not request.user.project_set.filter(pk=project.pk).exists():
		messages.error(request, 'You need to be logged in and the author of the project to do that')
		return safeRedirect(request, "index")

	form = ProjectForm(instance=project)

	if request.method == 'POST':
		form = ProjectForm(instance=project, data=request.POST, files=request.FILES)
		if form.is_valid():
			form.clean()
			form.save()
			return HttpResponseRedirect(reverse('project', args=[project.idname]))

	return render(request, "mainpage/project_edit.html", {'project': project, 'form': form})

def project_download(request, project_id):
	project = get_object_or_404(Project, Q(pk=project_id)|Q(idname=project_id))
	
	project.downloads += 1
	project.save()

	wrapper = FileWrapper(open(project.file.path, "rb"))
	response = HttpResponse(wrapper, content_type='application/zip')
	response['Content-Disposition'] = 'attachment; filename=%s' % smart_str(os.path.basename(project.file.name))
	response['Content-Length'] = os.path.getsize(project.file.path)
	return response

def project_create(request):
	if not request.user.is_authenticated():
		messages.error(request, 'You need to be logged in to create a project')
		return safeRedirect(request, "index")

	form = CreateForm()

	if request.method == 'POST':
		form = CreateForm(data=request.POST, files=request.FILES)
		if form.is_valid():
			form.clean()
			proj = form.save(request.user)
			return HttpResponseRedirect(reverse('project', args=[proj.idname]))

	return render(request, "mainpage/create.html", {'form': form})

def register_view(request):
	if request.user.is_authenticated():
		return HttpResponseRedirect(reverse('index'))

	form = RegForm()
	if request.method == 'POST':
		form = RegForm(data=request.POST)
		if form.is_valid():
			form.clean()
			data = {
				'username': form.cleaned_data['username'],
				'email': form.cleaned_data['email'],
				'password1': form.cleaned_data['password1'],
			}

			salt = str(uuid.uuid4().int)[:5]
			usernamesalt = data['username']
			data['activation_key'] = hashlib.sha1((salt + usernamesalt).encode('utf8')).hexdigest()
			form.send_email(data)
			form.save(data)
			messages.success(request, 'You have successfully registered. Please check your mail and click the activation link')
			return HttpResponseRedirect(reverse('index'))

	return render(request, "mainpage/register.html", {'form': form})

def activate(request, activation_key):
	try:
		ru = RegUser.objects.get(activation_key=activation_key)
		print(ru.user)
		if ru.user.is_active == False:
			ru.user.is_active = True
			ru.user.save()
			messages.success(request, 'Your account has been successfully activated. You can log in now')
		else:
			messages.warning(request, 'Your account is already activated')
	except RegUser.DoesNotExist as err:
		messages.error(request, 'Invalid activation id')

	return HttpResponseRedirect(reverse('index'))

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
	

	return safeRedirect(request, "index")

def logout_view(request):
	if not request.user.is_authenticated():
		messages.info(request, 'You are not logged in')
	else:
		logout(request)
		messages.info(request, 'You have been logged out')
	return safeRedirect(request, "index")

def profile(request, user_id):
	context = {'isauthor': False}
	u = User.objects.get(username=user_id)
	ru = RegUser.objects.get(user=u)
	if request.user.is_authenticated() and request.user == u:
		context['isauthor'] = True
		if request.method == 'POST':
			form = ProfileForm(data=request.POST, files=request.FILES, instance=ru)
			if form.is_valid():
				form.clean()
				form.save()
				messages.success(request, 'Info updated')
				ru = RegUser.objects.get(user=u)
		context['form'] = ProfileForm(instance=ru)


	table = UserProjectTable(u.project_set.all())
	table.paginate(page=request.GET.get('page',1), per_page=25)
	context['udata'] = ru
	context['table'] = table

	return render(request, "mainpage/profile.html", context)
