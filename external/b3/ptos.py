
__author__ = "ArmedGuy"
__version__ = "0.1"
import os, sys, re, time, thread
import urllib, urllib2
from threading import Thread
import b3
import b3.events
class PtosPlugin( b3.plugin.Plugin ):
    def onStartup(self):
        self._adminPlugin = self.console.getPlugin('admin')
        if not self._adminPlugin:
          # something is wrong, can't start without admin plugin
          self.error('Could not find admin plugin')
          return False
        # XML config
        self._ptosLocation = self.config.get('ptos', 'url')
        self._serverGuid = self.config.get('ptos', 'server_guid')
        self._serverKey = self.config.get('ptos', 'server_key')
        self.debug("url " + self._ptosLocation)
        # listen for client events
        self.registerEvent(b3.events.EVT_CLIENT_SAY)
        self.registerEvent(b3.events.EVT_CLIENT_TEAM_SAY)
        self.registerEvent(b3.events.EVT_CLIENT_PRIVATE_SAY)
        self.registerEvent(b3.events.EVT_CLIENT_CONNECT)
        self.registerEvent(b3.events.EVT_CLIENT_DISCONNECT)
        
    def onEvent(self, event):
        if not event.client or event.client.cid == None:
            return
        if event.type == b3.events.EVT_CLIENT_SAY:
			self.send_event('sayall', event.client, { 'text': event.data })
        if event.type == b3.events.EVT_CLIENT_TEAM_SAY:
            self.send_event('sayteam', event.client, { 'text': event.data })
        if event.type == b3.events.EVT_CLIENT_CONNECT:
            self.send_event('joined', event.client)
        if event.type == b3.events.EVT_CLIENT_DISCONNECT:
            self.send_event('left', event.client)
        if event.type == b3.events.EVT_CLIENT_KICK:
            self.send_event('kicked', event.client, { 'reason': event.data.reason })
        if event.type == b3.events.EVT_CLIENT_BAN:
            self.send_event('banned', event.client, { 'reason': event.data.reason })
        
    def send_event(self, type, client, data={}):
        params = []
        params.append(('server_key', self._serverKey))
        params.append(('server_guid', self._serverGuid))
        params.append(('player_name', client.name))
        params.append(('player_ip', client.ip))
        params.append(('player_guid', client.guid))
        params.append(('event_type', type))
        for key in data:
            params.append(('event_data['+key+']', data[key]))
        
        rawData = urllib.urlencode(params)
        self.debug(rawData)
        req = urllib2.Request(self._ptosLocation, rawData)
        req.add_header("Content-type", "application/x-www-form-urlencoded")
        response = urllib2.urlopen(req)
        response.close()