FROM node
EXPOSE 3000
RUN apt-get update && apt-get install -y --no-install-recommends liblcms2-utils && rm -rf /var/lib/apt/lists/*
RUN git clone --single-branch https://github.com/jpederson/node-colorvert-api.git /colorvert && cd /colorvert && npm install --production
WORKDIR /colorvert
ENTRYPOINT ["node", "server.js"]
