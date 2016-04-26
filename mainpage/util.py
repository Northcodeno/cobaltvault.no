from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse

def safeRedirect(request, fallback):
	if request.get_raw_uri() == request.META.get('HTTP_REFERER', '/'):
		return HttpResponseRedirect(reverse(fallback))
	else:
		return HttpResponseRedirect(request.META.get('HTTP_REFERER', '/'))