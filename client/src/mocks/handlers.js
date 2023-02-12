import { rest } from "msw";
import events from "../../mock_data/models/events.json";
import tickets from "../../mock_data/models/tickets.json";

const handlers = [
  rest.get("/events", (req, res, ctx) => {
    return res(ctx.json(events));
  }),
  rest.get("/events/:id", (req, res, ctx) => {
    return res(ctx.json(events[0]));
  }),

  rest.get("/tickets", (req, res, ctx) => {
    return res(ctx.json(tickets));
  }),
  rest.get("/tickets/:reference", (req, res, ctx) => {
    return res(ctx.json(tickets[0]));
  }),
  rest.post("/tickets", (req, res, ctx) => {
    return res(ctx.json({ success: true }));
  }),
  rest.post("/checkout/tickets/success", (req, res, ctx) => {
    return res(ctx.json({ success: true }));
  }),
];

export default handlers;
