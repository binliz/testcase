FROM node:18-alpine

RUN npm i npm@latest -g

WORKDIR /app
RUN chown node:node ./
USER node

ARG NODE_ENV=production
ENV NODE_ENV $NODE_ENV

COPY --chown=node:node socket/package*.json .

RUN npm ci
RUN npm install --no-optional && npm cache clean --force
COPY --chown=node:node socket/ .

EXPOSE 6001

CMD ["npm", "start"]
